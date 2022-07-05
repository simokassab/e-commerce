<?php

namespace App\Http\Requests\Product;

use App\Models\Settings\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            $titlesArray=[];
            $setting= Setting::where('title','products_fields_required')->first();
            if($setting){
                $titlesArray=explode(',', $setting->value);
            }

            return [

            'name' => 'required',
            'slug' => 'required | max:'.config('defaults.default_string_length').' | unique:products,slug,'.$this->id ?? null,
            'code' => 'required | max:'.config('defaults.default_string_length').' | unique:products,code,'.$this->id,
            'sku' => [Rule::when(in_array('sku',$titlesArray), 'required','nullable'),' max:'.config('defaults.default_string_length')],
            'type' => 'required | in:'.config('defaults.validation_default_types'),
            'quantity' => 'integer | gte:0 ',
            'reserved_quantity' => 'nullable | integer | gte:0',
            'minimum_quantity' => 'integer | gte:0  ',
            'summary' => [Rule::when(in_array('summary',$titlesArray), 'required','nullable')],
            'specification' => [Rule::when(in_array('specification',$titlesArray), 'required','nullable')],

            'image' => 'nullable | file
            | mimes:'.config('defaults.default_image_extentions').'
            | max:'.config('defaults.default_image_size').'
            | dimensions:max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),

            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'status' => 'required | in:'.config('defaults.validation_default_status'),
            'barcode' =>[Rule::when(in_array('barcode',$titlesArray), 'required','nullable'), 'max:'.config('defaults.default_string_length')],
            'height' => [Rule::when(in_array('height',$titlesArray), 'required','nullable'),'numeric'],
            'width' =>  [Rule::when(in_array('width',$titlesArray), 'required','nullable'),'numeric'],
            'length' =>  [Rule::when(in_array('length',$titlesArray), 'required','nullable'),'numeric'],
            'weight' =>  [Rule::when(in_array('weight',$titlesArray), 'required','nullable'),'numeric'],
            'is_disabled' => 'nullable | boolean ',
            'sort' => 'nullable | integer',
            'is_default_child' => ' boolean ',
            'parent_product_id'=> 'nullable | integer | exists:products,id',
            'category_id'=> 'required  | integer | exists:categories,id',
            'unit_id'=> 'required | integer | exists:units,id',
            'brand_id'=> [Rule::when(in_array('brand_id',$titlesArray), 'required','nullable'),'nullable' ,'integer ',' exists:brands,id'],
            'tax_id'=> [Rule::when(in_array('tax_id',$titlesArray), 'required','nullable'),'nullable' ,'integer ',' exists:brands,id'],
            'products_statuses_id'=> 'required | integer | exists:products_statuses,id',

            'categories.*.category_id' => 'required | integer | exists:categories,id',

            'fields.*.field_id' => 'required | exists:fields,id,entity,brand',
            'fields.*.field_value_id' =>  'nullable | integer | exists:fields_values,id',
            'fields.*.value'=> 'nullable',

            'images.*.image' => 'required | file
            | mimes:'.config('defaults.default_image_extentions').'
            | max:'.config('defaults.default_image_size').'
            | dimensions:max_width='.config('defaults.default_image_maximum_width').',max_height='.config('defaults.default_image_maximum_height'),
            'images.*.title' => 'required',
            'images.*.sort' => 'required | integer',

            'labels.*.label_id' => 'required | exists:labels,id',

            'prices.*.price_id' => 'required | exists:prices,id',
            'prices.*.price' => 'required | numeric | gte:0',
            'prices.*.discounted_price' => 'nullable | numeric | gte:0',

            'related_products.*.parent_product_id' => 'required | integer | exists:products,id',
            'related_products.*.child_product_id' => 'required | integer | exists:products,id',
            'related_products.*.child_quantity' => 'required | integer | gte:0',

            'tags.*.tag_id' => 'required | integer | exists:tags,id',

        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'The :attribute field is required.',

            'slug.required' => 'the :attribute field is required',
            'slug.max' => 'the maximum string length is :max',
            'slug.unique' => 'The :attribute already exists!',

            'code.required' => 'the :attribute field is required',
            'code.max' => 'the maximum string length is :max',
            'code.unique' => 'The :attribute already exists!',

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

            'unit_id.required' => 'the :attribute field is required',
            'unit_id.integer' => 'The :attribute must be an integer',
            'unit_id.exists' => 'The :attribute must be a valid unit',

            'brand_id.required' => 'the :attribute field is required',
            'brand_id.integer' => 'The :attribute must be an integer',
            'brand_id.exists' => 'The :attribute must be a valid brand',

            'tax_id.required' => 'the :attribute field is required',
            'tax_id.integer' => 'The :attribute must be an integer',
            'tax_id.exists' => 'The :attribute must be a valid tax',

            'products_statuses_id.required' => 'the :attribute field is required',
            'products_statuses_id.integer' => 'The :attribute must be an integer',
            'products_statuses_id.exists' => 'The :attribute must be a valid products_statuses',

        ];
    }
}
