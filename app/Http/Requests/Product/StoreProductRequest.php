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
            'slug' => 'required | max:'.config('defaults.default_string_length').' | unique:products,slug,'.$this->id,
            'code' => 'required | max:'.config('defaults.default_string_length').'| unique:products,code,'.$this->id,
            'sku' => 'required | max:'.config('defaults.default_string_length'),
            'type' => 'required | in:'.config('defaults.validation_default_types'),
            'quantity' => 'required | integer | gte:1',
            'reserved_quantity' => 'nullable | integer | gte:1',
            'minimum_quantity' => 'required | integer | gte:1',
            'summary' => 'required',
            'specification' => 'required',
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
            'category_id'=> '',
            'unit_id'=> '',
            'brand_id'=> '',
            'tax_id'=> '',
            'parent_product_id'=> '',
            'products_statuses_id'=> '',


        ];
    }
}
