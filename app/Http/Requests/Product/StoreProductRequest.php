<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\MainRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductRelated;
use App\Models\Settings\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class StoreProductRequest extends MainRequest
{
    private $productsRequiredSettingsArray = [];
    private $QuantityValue = 0;
    private $allowNegativeQuantity = 0;
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
    public function rules()
    {
        $productSettings = getSettings()->groupBy('title')->toArray();

        if ($productSettings) {
            $this->productsRequiredSettingsArray = explode(',', $productSettings['products_required_fields'][0]['value']) ?? "";
            $this->QuantityValue = $productSettings['products_quantity_greater_than_or_equal'][0]['value'] ?? 0;
            $this->allowNegativeQuantity = $productSettings['allow_negative_quantity'][0]['value'] ?? 0;
            $this->priceValue = $productSettings['products_prices_greater_than_or_equal'][0]['value'] ?? 0;
            $this->discountedPriceValue = $productSettings['products_discounted_price_greater_than_or_equal'][0]['value'] ?? 0;
        }

        $id = $this->route('product') ? $this->route('product')->id : null;

        $rules = [
            'name.en' => 'required',
            'name.ar' => 'required',
            'slug' => 'required | max:' . config('defaults.default_string_length') . ' | unique:products,slug,' . $id,
            'code' => 'required | max:' . config('defaults.default_string_length') . ' | unique:products,code,' . $id,
            'sku' => [Rule::when(in_array('sku',  $this->productsRequiredSettingsArray), 'required', 'nullable'), ' max:' . config('defaults.default_string_length')],
            'type' => 'required | in:' . Product::$prdouctTypes,
            'quantity' => [Rule::when(in_array($this->type, ['variable','service']), ['in:0'], 'required'), 'integer', 'gte:' . $this->QuantityValue],
            'reserved_quantity' => [Rule::when(in_array($this->type, ['variable','service']), ['in:0'], 'nullable'), 'integer', 'gte:0'],
            'minimum_quantity' => [Rule::when(in_array($this->type, ['variable','service']), ['in:0'], 'required'), 'integer', Rule::when(!$this->allowNegativeQuantity, ['gte:0'])],
            'summary' => [Rule::when(in_array('summary',  $this->productsRequiredSettingsArray), 'required', 'nullable')],
            'specification' => [Rule::when(in_array('specification',  $this->productsRequiredSettingsArray), 'required', 'nullable')],

            // 'image' => 'nullable | file
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

            'website_status' => 'required | in:' . Product::$productStatuses,
            'barcode' => [Rule::when(in_array('barcode',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'max:' . config('defaults.default_string_length')],
            'height' => [Rule::when(in_array('height',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'width' =>  [Rule::when(in_array('width',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'length' =>  [Rule::when(in_array('length',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'weight' =>  [Rule::when(in_array('weight',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'is_disabled' => 'nullable | boolean ',
            'sort' => 'nullable | integer',
            'is_default_child' => 'required | boolean',

            'parent_product_id' => [Rule::when($this->is_same_price_as_parent && $this->type == 'variable_child', 'required', 'nullable'), 'integer', 'exists:products,id'],
            'category_id' => 'required  | integer | exists:categories,id',
            'unit_id' => 'required | integer | exists:units,id',
            'brand_id' => [Rule::when(in_array('brand_id',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'nullable', 'integer ', ' exists:brands,id'],
            'tax_id' => [Rule::when(in_array('tax_id',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'nullable', 'integer ', ' exists:taxes,id'],
            'products_statuses_id' => 'required | integer | exists:products_statuses,id',
            'is_show_related_product' => 'required | boolean',
            'pre_order' => 'required | boolean',
            'bundle_reserved_quantity' => 'nullable | double',
            'bundle_price_status' =>  ['nullable', Rule::in(ProductRelated::$bundle_price_status)],
            'is_same_price_as_parent' => 'nullable | boolean',

            //FOR SORT
            'order.*.id' => 'required | integer | exists:products,id',
            'order.*.sort' => 'required | integer',

            // 'categories.*' => 'nullable',
            // 'categories.*.id' => 'exists:categories,id',

            'fields.*.field_id' => 'required | exists:fields,id,entity,product',
            'fields.*.type' => ['required', 'exists:fields,type,entity,product'],

            // 'images.*.image' => 'required | file
            // | mimes:' . config('defaults.default_image_extentions') . '
            // | max:' . config('defaults.default_image_size') . '
            // | dimensions:max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),
            // 'images_data.*.title' => 'required ',
            // 'images_data.*.sort' => 'required | integer',

            'labels.*' => 'exists:labels,id',

            'prices.*.price_id' => 'required | integer | exists:prices,id',
            'prices.*.price' => 'required | numeric | gte:' . $this->priceValue,
            'prices.*.discounted_price' => 'nullable | numeric | gte:' . $this->discountedPriceValue,

            'tags.*' => 'exists:tags,id',

            'related_products.*.child_product_id' => [Rule::when($this->type == 'bundle', ['required', 'integer', 'exists:products,id'])],
            'related_products.*.child_quantity' => [Rule::when($this->type == 'bundle', ['required', 'integer', 'gte:' . $this->QuantityValue])],
            'related_products.*.name' => 'nullable',
            'related_products.*.child_name_status' => ['required', Rule::in(ProductRelated::$childNameStatuses)],


            'product_variations' => [Rule::when($this->type == 'variable', 'required', 'nullable')],
            'product_variations.*.code' => 'required | max:' . config('defaults.default_string_length') . ' | unique:products,code,' . $id,
            'product_variations.*.sku' => [Rule::when(in_array('sku',  $this->productsRequiredSettingsArray), 'required', 'nullable'), ' max:' . config('defaults.default_string_length')],

            'product_variations.*.quantity' => ['required', 'integer', 'gte:' . $this->QuantityValue],
            'product_variations.*.reserved_quantity' => ['nullable', 'integer', 'gte:0'],
            'product_variations.*.minimum_quantity' => ['required', 'integer', Rule::when(!$this->allowNegativeQuantity, ['gte:0'])],

            'product_variations.*.summary' => [Rule::when(in_array('summary',  $this->productsRequiredSettingsArray), 'required', 'nullable')],
            'product_variations.*.specification' => [Rule::when(in_array('specification',  $this->productsRequiredSettingsArray), 'required', 'nullable')],
            'product_variations.*.meta_title.en' => 'nullable',
            'product_variations.*.meta_title.ar' => 'nullable',
            'product_variations.*.meta_description.en' => 'nullable',
            'product_variations.*.meta_description.ar' => 'nullable',
            'product_variations.*.meta_keyword.en' => 'nullable',
            'product_variations.*.meta_keyword.ar' => 'nullable',
            'product_variations.*.description.en' => 'nullable',
            'product_variations.*.description.ar' => 'nullable',
            'product_variations.*.barcode' => [Rule::when(in_array('barcode',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'max:' . config('defaults.default_string_length')],
            'product_variations.*.height' => [Rule::when(in_array('height',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'product_variations.*.width' =>  [Rule::when(in_array('width',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'product_variations.*.length' =>  [Rule::when(in_array('length',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'product_variations.*.weight' =>  [Rule::when(in_array('weight',  $this->productsRequiredSettingsArray), 'required', 'nullable'), 'numeric'],
            'product_variations.*.is_default_child' => 'required | boolean',
            'product_variations.*.is_same_price_as_parent' => 'required | boolean',
            'product_variations.*.products_statuses_id' => 'required | integer | exists:products_statuses,id',
            'product_variations.*.is_same_dimensions_as_parent' => 'required | boolean',

            // 'product_variations.*.images.*.image' => 'required | file
            // | mimes:' . config('defaults.default_image_extentions') . '
            // | max:' . config('defaults.default_image_size') . '
            // | dimensions:max_width=' . config('defaults.default_image_maximum_width') . ',max_height=' . config('defaults.default_image_maximum_height'),
            // 'product_variations.*.images.*.title' => 'required ',
            // 'product_variations.*.images.*.sort' => 'required | integer',



        ];
        if ($this->has('fields')) {
            $rules =  array_merge($rules, Product::generateValidationRules($this->fields, 'fields'));
        }
        if ($this->has('attributes_fields')) {
            $rules =  array_merge($rules, Product::generateValidationRules($this->attributes_fields, 'attributes_fields'));
        }

        if ($this->type == 'variable') {
            if ($this->has('product_variations')) {
                foreach ($this->product_variations as $key => $variation) {
                    if (!array_key_exists('is_same_price_as_parent', $variation)) {
                        break;
                    }
                    if (!$variation['is_same_price_as_parent']) {
                        $pricesRulesArray['product_variations.*.prices.*.price_id'] = 'required | integer | exists:prices,id';
                        $pricesRulesArray['product_variations.*.prices.*.price'] = 'required | numeric | gte:' . $this->priceValue;
                        $pricesRulesArray['product_variations.*.prices.*.discounted_price'] = 'nullable | numeric | gte:' . $this->discountedPriceValue;

                        $rules = array_merge($rules, $pricesRulesArray);
                    }
                    if (array_key_exists('fields', $variation))
                        $rules =  array_merge($rules, Product::generateValidationRules($variation['fields'], 'product_variations.*.fields'));

                    if (array_key_exists('attributes_fields',$variation))
                        $rules =  array_merge($rules, Product::generateValidationRules($variation['attributes_fields'], 'product_variations.*.attributes_fields'));
                }
            }
        }


        return $rules;
    }


    public function messages()
    {
        return [
            'name.en' => 'The field is required.',
            'name.ar' => 'The field is required.',

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
            'quantity.gte' => 'The :attribute must be greater than or equal to ' . $this->QuantityValue,
            'quantity.in' => 'The :attribute must be 0',

            'reserved_quantity.required' => 'the :attribute field is required',
            'reserved_quantity.integer' => 'The :attribute must be an integer',
            'reserved_quantity.gte' => 'The :attribute must be greater than or equal to ' . $this->allowNegativeQuantity,
            'reserved_quantity.in' => 'The :attribute must be 0',

            'minimum_quantity.required' => 'the :attribute field is required',
            'minimum_quantity.integer' => 'The :attribute must be an integer',
            'minimum_quantity.gte' => 'The :attribute must be greater than or equal to ' . $this->allowNegativeQuantity,
            'minimum_quantity.in' => 'The :attribute must be 0',

            'summary.required' => 'the :attribute field is required',

            'specification.required' => 'the :attribute field is required',

            'image.file' => 'The input is not an image',
            'image.max' => 'The maximum :attribute size is :max.',
            'image.mimes' => 'Invalid extention.',
            'image.dimensions' => 'Invalid dimentions! maximum(' . config('defaults.default_image_maximum_width') . 'x' . config('defaults.default_image_maximum_height') . ')',

            'website_status.required' => 'the :attribute field is required',
            'website_status.in' => 'The :attribute is not a valid status',

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

            'tags.*.tag_id.required' => 'the tag field is required',
            'tags.*.tag_id.integer' => 'The tag must be an integer',
            'tags.*.tag_id.exists' => 'The tag must be a valid tag',

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


            'order.*.id.required' => 'The id is required',
            'order.*.id.integer' => 'The id should be an integer',
            'order.*.id.exists' => 'The id is not exists',
            'order.*.sort.required' => 'The sort is required',
            'order.*.sort.integer' => 'The sort should be an integer',

            'product_varitations.*.code.required' => 'The code is required',
            'product_varitations.*.code.max' => 'The code should be less than :max characters',
            'product_varitations.*.code.string' => 'The code should be a string',
            'product_varitations.*.code.unique' => 'The code is already exists',


        ];
    }
}
