<?php

namespace App\Http\Requests\Product;

use App\Models\Settings\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    private $productsRequiredSettingsArray = [];
    private $QuantityValue = 0;
    private $minimumAndReservedQuantityValue = 0;
    private $priceValue = 0;
    private $discountedPriceValue = 0;
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


        $titlesArray = [
            'products_required_fields',
            'products_quantity_greater_than_or_equal',
            'products_minimum_and_reserved_quantity_greater_than_or_equal',
            'products_prices_greater_than_or_equal',
            'products_discounted_price_greater_than_or_equal',
        ];

        $productSettings = Setting::whereIn('title', $titlesArray)->get();
        if ($productSettings) {
            foreach ($productSettings as $key => $value) {
                if ($value->title == 'products_required_fields')
                  $this->productsRequiredSettingsArray = explode(',', $value->value);
                if ($value->title == 'products_quantity_greater_than_or_equal')
                  $this->QuantityValue = (int)$value->value;
                if ($value->title == 'products_minimum_and_reserved_quantity_greater_than_or_equal')
                $this->minimumAndReservedQuantityValue = (int)$value->value;
                if ($value->title == 'products_prices_greater_than_or_equal')
                $this->priceValue = (int)$value->value;
                if ($value->title == 'products_discounted_price_greater_than_or_equal')
                $this->discountedPriceValue = (int)$value->value;
            }
        }

        return [
            'name' => 'required',
            'slug' => 'required | max:' . config('defaults.default_string_length') . ' | unique:products,slug,' . $this->id ?? null,
            'code' => 'required | max:' . config('defaults.default_string_length') . ' | unique:products,code,' . $this->id ?? null,
            'sku' => [Rule::when(in_array('sku',  $this->productsRequiredSettingsArray), 'required', 'nullable'), ' max:' . config('defaults.default_string_length')],
            'type' => 'required | in:' . config('defaults.validation_default_types'),
            'quantity' => [Rule::when($request->type == 'variable', ['in:0'], 'required'), 'integer', 'gte:' . $this->QuantityValue],
            'reserved_quantity' => [Rule::when($request->type == 'variable', ['in:0'], 'nullable'), 'integer', 'gte:' . $this->minimumAndReservedQuantityValue],
            'minimum_quantity' => [Rule::when($request->type == 'variable', ['in:0'], 'required'), 'integer', 'gte:' . $this->minimumAndReservedQuantityValue],
            'summary' => [Rule::when(in_array('summary',  $this->productsRequiredSettingsArray), 'required', 'nullable')],
            'specification' => [Rule::when(in_array('specification',  $this->productsRequiredSettingsArray), 'required', 'nullable')],

            'image' => 'nullable | file
            | mimes:' . config('defaults.default_image_extentions') . '
            | max:' . config('defaults.default_image_size') . '
            | dimensions:max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),

            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
            'meta_keyword' => 'nullable',
            'description' => 'nullable',
            'status' => 'required | in:' . config('defaults.validation_default_status'),
            'barcode' => [Rule::when(in_array('barcode',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'max:' . config('defaults.default_string_length')],
            'height' => [Rule::when(in_array('height',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'width' =>  [Rule::when(in_array('width',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'length' =>  [Rule::when(in_array('length',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'weight' =>  [Rule::when(in_array('weight',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'is_disabled' => 'nullable | boolean ',
            'sort' => 'nullable | integer',
            'is_default_child' => 'boolean',

            'parent_product_id' => [Rule::when($request->isSamePriceAsParent && $request->type == 'variable_child', 'required', 'nullable'), 'integer', 'exists:products,id'],
            'category_id' => 'required  | integer | exists:categories,id',
            'unit_id' => 'required | integer | exists:units,id',
            'brand_id' => [Rule::when(in_array('brand_id',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'nullable', 'integer ', ' exists:brands,id'],
            'tax_id' => [Rule::when(in_array('tax_id',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'nullable', 'integer ', ' exists:brands,id'],
            'products_statuses_id' => 'required | integer | exists:products_statuses,id',

            'categories' => 'required | array',
            'categories.*' => 'exists:categories,id',

            'fields.*.field_id' => 'required | integer | exists:fields,id,entity,product',
            'fields.*.field_value_id' =>  'nullable | integer | exists:fields_values,id',
            'fields.*.value' => 'nullable | max:' . config('defaults.default_string_length_2'),

            'images.*.image' => 'required | file
            | mimes:' . config('defaults.default_image_extentions') . '
            | max:' . config('defaults.default_image_size') . '
            | dimensions:max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),
            'images.*.title' => 'required ',
            'images.*.sort' => 'required | integer',

            'labels' => 'required | array',
            'labels.*' => 'exists:labels,id',

            'prices.*.price_id' => 'required | integer | exists:prices,id',
            'prices.*.price' => 'required | numeric | gte:' .$this->priceValue,
            'prices.*.discounted_price' => 'nullable | numeric | gte:' .$this->discountedPriceValue,

            'related_products.*.child_product_id' => [Rule::when($request->type == 'bundle', 'required'), 'integer', 'exists:products,id'],
            'related_products.*.child_quantity' => [Rule::when($request->type == 'bundle', 'required'), 'integer', 'gte:' . $this->QuantityValue],

            'tags' => 'required | array',
            'tags.*' => 'exists:tags,id',

            'order.*.id' => 'required | integer | exists:products,id',
            'order.*.sort' => 'required | integer',
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
            'quantity.gte' => 'The :attribute must be greater than or equal to '.$this->QuantityValue,
            'quantity.in' => 'The :attribute must be 0',

            'reserved_quantity.required' => 'the :attribute field is required',
            'reserved_quantity.integer' => 'The :attribute must be an integer',
            'reserved_quantity.gte' => 'The :attribute must be greater than or equal to '.$this->minimumAndReservedQuantityValue,
            'reserved_quantity.in' => 'The :attribute must be 0',

            'minimum_quantity.required' => 'the :attribute field is required',
            'minimum_quantity.integer' => 'The :attribute must be an integer',
            'minimum_quantity.gte' => 'The :attribute must be greater than or equal to '.$this->minimumAndReservedQuantityValue,
            'minimum_quantity.in' => 'The :attribute must be 0',

            'summary.required' => 'the :attribute field is required',

            'specification.required' => 'the :attribute field is required',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions! maximum(' . config('defaults.default_image_maximum_width') . 'x' . config('defaults.default_image_maximum_height') . ')',

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

            'parent_product_id.integer' => 'The parent product must be an integer',
            'parent_product_id.exists' => 'The parent product must be a valid product',

            'category_id.required' => 'the category field is required',
            'category_id.integer' => 'The category must be an integer',
            'category_id.exists' => 'The category must be a valid category',

            'unit_id.required' => 'the unit field is required',
            'unit_id.integer' => 'The unit must be an integer',
            'unit_id.exists' => 'The unit must be a valid unit',

            'brand_id.required' => 'the brand field is required',
            'brand_id.integer' => 'The brand must be an integer',
            'brand_id.exists' => 'The brand must be a valid brand',

            'tax_id.required' => 'the tax field is required',
            'tax_id.integer' => 'The tax must be an integer',
            'tax_id.exists' => 'The tax must be a valid tax',

            'products_statuses_id.required' => 'the status field is required',
            'products_statuses_id.integer' => 'The status must be an integer',
            'products_statuses_id.exists' => 'The status must be a valid products_statuses',

            'categories.*.category_id.required' => 'the category field is required',
            'categories.*.category_id.integer' => 'The category must be an integer',
            'categories.*.category_id.exists' => 'The category must be a valid category',

            'fields.*.field_id.required' => 'the field field is required',
            'fields.*.field_id.integer' => 'The field must be an integer',
            'fields.*.field_id.exists' => 'The field must be a valid field',
            'fields.*.field_value_id.integer' => 'The field value must be an integer',
            'fields.*.field_value_id.exists' => 'The field value must be a valid field_value',

            'images.*.image.required' => 'the image field is required',
            'images.*.image.file' => 'The input is not an image',
            'images.*.image.max' => 'The maximum image size is :max.',
            'images.*.image.mimes' => 'Invalid extention.',
            'images.*.image.dimensions' => 'Invalid dimentions! maximum(' . config('defaults.default_image_maximum_width') . 'x' . config('defaults.default_image_maximum_height') . ')',
            'images.*.title.required' => 'the title field is required',
            'images.*.title.max' => 'the maximum string length is :max',
            'images.*.title.string' => 'The title must be a string',
            'images.*.sort.required' => 'the sort field is required',
            'images.*.sort.integer' => 'The sort must be an integer',


            'labels.*.label_id.required' => 'the label field is required',
            'labels.*.label_id.integer' => 'The label must be an integer',
            'labels.*.label_id.exists' => 'The label must be a valid label',

            'prices.*.price_id.required' => 'the price field is required',
            'prices.*.price_id.integer' => 'The price must be an integer',
            'prices.*.price_id.exists' => 'The price must be a valid price',
            'prices.*.price.required' => 'the price field is required',
            'prices.*.price.numeric' => 'The price must be a number',
            'prices.*.price.gte' => 'The price must be greater than or equal to :value.',
            'prices.*.discount_price.numeric' => 'The discount_price must be a number',
            'prices.*.discount_price.gte' => 'The discount_price must be greater than or equal to :value.',

            'related_products.*.parent_product_id.required' => 'the parent product field is required',
            'related_products.*.parent_product_id.integer' => 'The parent product must be an integer',
            'related_products.*.parent_product_id.exists' => 'The parent product must be a valid product',
            'related_products.*.child_product_id.required' => 'the child product field is required',
            'related_products.*.child_product_id.integer' => 'The child product must be an integer',
            'related_products.*.child_product_id.exists' => 'The child product must be a valid product',
            'related_products.*.child_quantity.required' => 'the child quantity field is required',
            'related_products.*.child_quantity.integer' => 'The child quantitymust be an integer',
            'related_products.*.child_quantity.gte' => 'The child quantitymust be greater than or equal to :value',

            'tags.*.tag_id.required' => 'the tag field is required',
            'tags.*.tag_id.integer' => 'The tag must be an integer',
            'tags.*.tag_id.exists' => 'The tag must be a valid tag',

            'order.*.id.required' => 'The id is required',
            'order.*.id.integer' => 'The id should be an integer',
            'order.*.id.exists' => 'The id is not exists',
            'order.*.sort.required' => 'The sort is required',
            'order.*.sort.integer' => 'The sort should be an integer',

        ];
    }
}
