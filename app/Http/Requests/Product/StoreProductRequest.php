<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            // 'slug' => 'required | max:'.config('defaults.default_string_length').' | unique:products,slug,'.$this->id ?? null,
            // 'code' => 'required | max:'.config('defaults.default_string_length').' | unique:products,code,'.$this->id,
            'sku' => 'required | max:'.config('defaults.default_string_length'),
            'type' => 'required | in:'.config('defaults.validation_default_types'),
            'quantity' => 'required | integer | gte:0',
            'reserved_quantity' => 'nullable | integer | gte:0',
            'minimum_quantity' => 'required | integer | gte:0',
            'summary' => 'required',
            'specification' => 'required',

            'image' => 'nullable | file
            | mimes:'.config('defaults.default_image_extentions').'
            | max:'.config('defaults.default_image_size').'
            | dimensions:max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),

            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'status' => 'required | in:'.config('defaults.validation_default_status'),
            'barcode' => 'required | max:'.config('defaults.default_string_length'),
            'height' => 'nullable | numeric',
            'width' => 'nullable | numeric',
            'length' => 'nullable | numeric',
            'weight' => 'nullable | numeric',
            'is_disabled' => 'nullable | boolean',
            'sort' => 'nullable | integer',
            'is_default_child' => 'required | boolean',
            'parent_product_id'=> 'nullable | integer | exists:products,id',
            'category_id'=> 'required  | integer | exists:categories,id',
            'unit_id'=> 'required | integer | exists:units,id',
            'brand_id'=> 'required | integer | exists:brands,id',
            'tax_id'=> 'required | integer | exists:taxes,id',
            // 'products_statuses_id'=> '',

        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'The :attribute field is required.',

            // 'slug.required' => 'the :attribute field is required',
            // 'slug.max' => 'the maximum string length is :max',
            // 'slug.unique' => 'The :attribute already exists!',

            // 'code.required' => 'the :attribute field is required',
            // 'code.max' => 'the maximum string length is :max',
            // 'code.unique' => 'The :attribute already exists!',

            'sku.required' => 'the :attribute field is required',
            'sku.max' => 'the maximum string length is :max',

            'type.required' => 'the :attribute field is required',
            'type.in' => 'The :attribute is not a valid type',

            'quantity.required' => 'the :attribute field is required',
            'quantity.integer' => 'The :attribute must be an integer',

            'reserved_quantity.required' => 'the :attribute field is required',
            'reserved_quantity.integer' => 'The :attribute must be an integer',

            'minimum_quantity.required' => 'the :attribute field is required',
            'minimum_quantity.integer' => 'The :attribute must be an integer',

            'summary.required' => 'the :attribute field is required',

            'specification.required' => 'the :attribute field is required',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions! maximum('.config('defaults.default_image_maximum_width').'x'.config('defaults.default_image_maximum_height').')',

            'status.required' => 'the :attribute field is required',
            'status.in' => 'The :attribute is not a valid status',

            'barcode.required' => 'the :attribute field is required',
            'barcode.max' => 'the maximum string length is :max',

            'height.numeric' => 'The :attribute must be a number',
            'width.numeric' => 'The :attribute must be a number',
            'length.numeric' => 'The :attribute must be a number',
            'weight.numeric' => 'The :attribute must be a number',

            'is_disabled.boolean' => 'The :attribute must be a boolean',

            'sort.integer' => 'The :attribute must be an integer',

            'is_default_child.required' => 'the :attribute field is required',
            'is_default_child.boolean' => 'The :attribute must be a boolean',

            'parent_product_id.integer' => 'The :attribute must be an integer',
            'parent_product_id.exists' => 'The :attribute must be a valid product',

            'category_id.required' => 'the :attribute field is required',
            'category_id.integer' => 'The :attribute must be an integer',
            'category_id.exists' => 'The :attribute must be a valid category',

            // 'unit_id.required' => 'the :attribute field is required',
            // 'unit_id.integer' => 'The :attribute must be an integer',
            // 'unit_id.exists' => 'The :attribute must be a valid unit',

            // 'brand_id.required' => 'the :attribute field is required',
            // 'brand_id.integer' => 'The :attribute must be an integer',
            // 'brand_id.exists' => 'The :attribute must be a valid brand',

            // 'tax_id.required' => 'the :attribute field is required',
            // 'tax_id.integer' => 'The :attribute must be an integer',
            // 'tax_id.exists' => 'The :attribute must be a valid tax',

            // 'products_statuses_id.required' => 'the :attribute field is required',
            // 'products_statuses_id.integer' => 'The :attribute must be an integer',
            // 'products_statuses_id.exists' => 'The :attribute must be a valid products_statuses',

        ];
    }
}
