<?php

namespace App\Services\Product;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductField;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductLabel;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductRelated;
use App\Models\Product\ProductTag;
use App\Services\Category\CategoryService;
use Carbon\Carbon;
use DateTime;
use Error;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ProductService
{

    public function storeAdditionalProductData($request, $product, $childrenIds)
    {

        //$request=(object)$request;

        $this->storeAdditionalCategrories($request, $product, $childrenIds)
            ->storeAdditionalFields($request, $product)
            ->removeAdditionalImages($request)
            ->storeAdditionalImages($request, $product)
            ->storeAdditionalLabels($request, $product, $childrenIds)
            ->storeAdditionalTags($request, $product, $childrenIds)
            ->storeAdditionalPrices($request, $product, $childrenIds)
            ->storeAdditionalAttributes($request, $product);
    }
    public function storeAdditionalCategrories($request, $product, $childrenIds)
    {
        $categoryCheck = ProductCategory::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $product->id;

        if (!$request->has('categories'))
            return $this;

        $categoriesIdsArray = [];
        $oneLevelCategoryArray = CategoryService::loopOverMultiDimentionArray($request->categories);
        foreach ($childrenIdsArray as $key => $child) {
            foreach ($oneLevelCategoryArray as $key => $category) {
                $isChecked = filter_var($category['checked'], FILTER_VALIDATE_BOOLEAN);
                if ($isChecked) {
                    $categoriesIdsArray[] = [
                        'product_id' => $child,
                        'category_id' => $category['id'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }
        }
        if (ProductCategory::insert($categoriesIdsArray))
            return $this;

        throw new Exception('Error while storing product categories');
    }
    public function storeAdditionalFields($request, $product)
    {
        DB::beginTransaction();
        try {
            if (!$request->has('fields'))
                return $this;

            if (is_null($request->fields))
                return $this;

            $fieldCheck = ProductField::where('product_id', $product->id)->delete();

            $data = [];
            foreach ($request->fields as $index => $field) {
                if (!in_array($field['type'], config('defaults.fields_types')))
                    throw new Exception('Invalid fields type');

                if ($field['type'] == 'select') {
                    throw_if(!is_numeric($field['value'], new Exception('Invalid value')));
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  (int)$field['value'],
                        'value' => null,
                    ];
                } elseif (($field['type']) == 'checkbox') {
                    throw_if(!is_bool($field['value'], new Exception('Invalid value')));
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  null,
                        'value' => (bool)$field['value'],
                    ];
                } elseif (($field['type']) == 'date') {
                    throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $field['value']) !== false, new Exception('Invalid value'));
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  null,
                        'value' => Carbon::createFromFormat('Y-m-d H:i:s', $field['value']),
                    ];
                } elseif (($field['type']) == 'text' || gettype($field['type']) == 'textarea') {
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$field['field_id'],
                        'field_value_id' =>  null,
                        'value' => ($field['value']),
                    ];
                } else {
                    continue;
                }
            }
            $create = ProductField::query()->create($data);

            DB::commit();
            return $this;
        } catch (Exception $error) {
            dd($error);
            DB::rollback();
        } catch (Error $error) {
            dd($error);
            DB::rollback();
        }

        throw new Exception('Error while storing product fields');
    }
    public function storeAdditionalAttributes($request, $product)
    {
        DB::beginTransaction();

        try {

            if (!$request->has('attributes'))
                return $this;

            if (is_null($request->attributes))
                return $this;

            $attributesCheck = ProductField::where('product_id', $product->id)->delete();

            $data = [];
            foreach ($request->attributes as $index => $attribute) {
                if (!in_array($attribute['type'], config('defaults.fields_types')))
                    throw new Exception('Invalid fields type');

                if ($attribute['type'] == 'select') {
                    throw_if(!is_numeric($attribute['value'], new Exception('Invalid value')));
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  (int)$attribute['value'],
                        'value' => null,
                    ];
                } elseif (($attribute['type']) == 'checkbox') {
                    throw_if(!is_bool($attribute['value'], new Exception('Invalid value')));
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  null,
                        'value' => (bool)$attribute['value'],
                    ];
                } elseif (($attribute['type']) == 'date') {
                    throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $attribute['value']) !== false, new Exception('Invalid value'));
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  null,
                        'value' => Carbon::createFromFormat('Y-m-d H:i:s', $attribute['value']),
                    ];
                } elseif (($attribute['type']) == 'text' || gettype($attribute['type']) == 'textarea') {
                    $data = [
                        'product_id' => $product->id,
                        'field_id' => (int)$attribute['field_id'],
                        'field_value_id' =>  null,
                        'value' => ($attribute['value']),
                    ];
                } else {
                    continue;
                }
            }
            $create = ProductField::query()->create($data);
            DB::commit();
            return $this;
        } catch (Exception $error) {
            dd($error);
            DB::rollback();
        } catch (Error $error) {
            dd($error);
            DB::rollback();
        }

        throw new Exception('Error while storing product attributes');
    }
    public function removeAdditionalImages($request)
    {

        if (!$request->has('images_deleted') || is_null($request->images_deleted))
            return $this;

        if (!ProductImage::whereIn('id', $request->images_deleted)->delete()) {
            throw new Exception('Error while deleting product images');
        }
        return $this;
    }
    public function storeAdditionalImages($request, $product)
    {
        //$request=(object)$request;

        if (!$request->has('images') || is_null($request->images)) {
            return $this;
        }
        if (count($request->images) != count($request->images_data)) {
            throw new Exception('Images and images_data count is not equal');
        }

        $data = [];
        foreach ($request->images as $index => $image) {
            $imagePath = "";
            if ($request->file('images') && !is_string($request->file('images')))
                $imagePath = uploadImage($image, config('images_paths.product.images'));

            $data[] = [
                'product_id' => $product->id,
                'image' => $imagePath,
                'title' => json_encode($request->images_data[$index]['title']),
                'sort' => $request->images_data[$index]['sort'],
                'created_at'  => Carbon::now()->toDateString(),
                'updated_at' => Carbon::now()->toDateString(),
            ];
        }


        if (ProductImage::insert($data)) {
            return $this;
        }
        throw new Exception('Error while storing product images');
    }
    public function storeAdditionalLabels($request, $product, $childrenIds)
    {
        //$request=(object)$request;
        $labelCheck = ProductLabel::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();

        if (!$request->has('labels'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $product->id;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->labels as $index => $label) {
                $data[] = [
                    'product_id' => $child,
                    'label_id' => $label,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ];
            }
        }

        if (ProductLabel::insert($data)) {
            return $this;
        }

        throw new Exception('Error while storing product categories');
    }
    public function storeAdditionalTags($request, $product, $childrenIds)
    {
        //$request=(object)$request;
        $tagCheck = ProductTag::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();

        if (!$request->has('tags'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $product->id;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->tags as $index => $tag) {
                $data[] = [
                    'product_id' => $child,
                    'tag_id' => $tag,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ];
            }
        }

        if (ProductTag::insert($data)) {
            return $this;
        }

        throw new Exception('Error while storing product tags');
    }
    // TYPE BUNDLE
    public function storeAdditionalBundle($request, $product)
    {
        //$request=(object)$request;
        $bundleCheck = ProductRelated::where('parent_product_id', $product->id)->delete();

        if ($request->type == 'bundle') {
            foreach ($request->related_products as $related_product => $value) {
                $data[$related_product] = [
                    'parent_product_id' => $product->id,
                    'child_product_id' => $value['child_product_id'],
                    'child_name_status' => array_key_exists('child_name_status', $value) ? $value['child_name_status'] : null,
                    'name' => array_key_exists('name', $value) ? json_encode($value['name']) : "",
                    'child_quantity' => $value['child_quantity'],
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            }
            ProductRelated::insert($data);
            // $this->calculateReservedQuantity($request, $product);
        }
        return $this;
    }
    // END OF TYPE BUNDLE
    public function storeAdditionalPrices($request, $product)
    {
        //$request=(object)$request;
        $priceCheck = ProductPrice::where('product_id', $product->id)->delete();

        if ($request->has('prices')) {
            $pricesArray =  [];
            foreach ($request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $product->id;
                $pricesArray[$price]["price_id"] = $value['price_id'];
                $pricesArray[$price]["price"] = $value['price'];
                $pricesArray[$price]["discounted_price"] = array_key_exists('discounted_price', $value) ? $value['discounted_price'] : 0;
                $pricesArray[$price]["created_at"] = Carbon::now()->toDateTimeString();
                $pricesArray[$price]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($pricesArray);
        }
        return $this;
    }
    public static function deleteRelatedDataForProduct(Product $product)
    {

        DB::beginTransaction();
        try {
            $data = [
                ProductCategory::class,
                ProductField::class,
                ProductImage::class,
                ProductLabel::class,
                ProductPrice::class,
                ProductTag::class
            ];

            $productType = $product->type ?? '';
            if ($productType == 'variable') {

                if (!$product->has('children'))
                    return;

                $productChildren = $product->children->pluck('id');
                foreach ($data as $table) {
                    $table::whereIn('product_id', $productChildren)->delete();
                }
                Product::whereIn('id', $productChildren)->delete();
            }

            ProductRelated::where('parent_product_id', $product->id)->delete();
            foreach ($data as $table) {
                $table::where('product_id', $product->id)->delete();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    // TYPE VARIABLE
    public function storeFieldsForVariations($fieldsArray, $childrenIds)
    {
        DB::beginTransaction();
        try {
            if (is_null($fieldsArray)  || count($fieldsArray) == 0)
                return $this;

            $fieldCheck = ProductField::whereIn('product_id', $childrenIds)->delete();

            $data = [];
            foreach ($childrenIds as $key => $child) {
                foreach ($fieldsArray as $index => $field) {
                    if (!in_array($field['type'], config('defaults.fields_types')))
                        throw new Exception('Invalid fields type');

                    if ($field['type'] == 'select') {
                        throw_if(!is_numeric($field['value'], new Exception('Invalid value')));
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  (int)$field['value'],
                            'value' => null,
                        ];
                    } elseif (($field['type']) == 'checkbox') {
                        throw_if(!is_bool($field['value'], new Exception('Invalid value')));
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  null,
                            'value' => (bool)$field['value'],
                        ];
                    } elseif (($field['type']) == 'date') {
                        throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $field['value']) !== false, new Exception('Invalid value'));
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  null,
                            'value' => Carbon::createFromFormat('Y-m-d H:i:s', $field['value']),
                        ];
                    } elseif (($field['type']) == 'text' || gettype($field['type']) == 'textarea') {
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$field['field_id'],
                            'field_value_id' =>  null,
                            'value' => ($field['value']),
                        ];
                    } else {
                        continue;
                    }
                }
            }
            $create = ProductField::query()->create($data);

            DB::commit();
            return $this;
        } catch (Exception $error) {
            dd($error);
            DB::rollback();
        } catch (Error $error) {
            dd($error);
            DB::rollback();
        }

        throw new Exception('Error while storing product fields');
    }
    public function storeAttributesForVariations($attributesArray, $childrenIds)
    {
        DB::beginTransaction();
        try {
            if (is_null($attributesArray) || count($attributesArray) == 0)
                return $this;

            $attributesCheck = ProductField::whereIn('product_id', $childrenIds)->delete();
            $data = [];

            foreach ($childrenIds as $key => $child) {
                foreach ($attributesArray as $index => $attribute) {
                    if (!in_array($attribute['type'], config('defaults.fields_types')))
                        throw new Exception('Invalid fields type');

                    if ($attribute['type'] == 'select') {
                        throw_if(!is_numeric($attribute['value'], new Exception('Invalid value')));
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$attribute['field_id'],
                            'field_value_id' =>  (int)$attribute['value'],
                            'value' => null,
                        ];
                    } elseif (($attribute['type']) == 'checkbox') {
                        throw_if(!is_bool($attribute['value'], new Exception('Invalid value')));
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$attribute['field_id'],
                            'field_value_id' =>  null,
                            'value' => (bool)$attribute['value'],
                        ];
                    } elseif (($attribute['type']) == 'date') {
                        throw_if(Carbon::createFromFormat('Y-m-d H:i:s', $attribute['value']) !== false, new Exception('Invalid value'));
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$attribute['field_id'],
                            'field_value_id' =>  null,
                            'value' => Carbon::createFromFormat('Y-m-d H:i:s', $attribute['value']),
                        ];
                    } elseif (($attribute['type']) == 'text' || gettype($attribute['type']) == 'textarea') {
                        $data = [
                            'product_id' => $child,
                            'field_id' => (int)$attribute['field_id'],
                            'field_value_id' =>  null,
                            'value' => ($attribute['value']),
                        ];
                    } else {
                        continue;
                    }
                }
            }
            $create = ProductField::query()->create($data);

            DB::commit();
            return $this;
        } catch (Exception $error) {
            dd($error);
            DB::rollback();
        } catch (Error $error) {
            dd($error);
            DB::rollback();
        }

        throw new Exception('Error while storing product fields');
    }
    public function removeImagesForVariations($imagesDeletedArray, $childrenIds)
    {
        if (is_null($imagesDeletedArray))
            return $this;

        $imagesIdsArray = [];
        foreach ($childrenIds as $key => $child) {
            foreach ($imagesDeletedArray as $key => $value) {
                $imagesIdsArray = $value[$key];
            }
            ProductImage::whereIn('id', $imagesIdsArray)->delete();
        }
    }
    public function storeImagesForVariations($imagesArray, $imagesData, $childrenIds)
    {
        if (is_null($imagesArray) || is_null($imagesData))
            return $this;

        $data = [];
        foreach ($childrenIds as $key => $child) {
            foreach ($imagesArray as $index => $image) {
                $imagePath = uploadImage($image, config('images_paths.product.images'));
                $data[] = [
                    'product_id' => $child,
                    'image' => $imagePath,
                    'title' => json_encode($imagesData[$index]['title']),
                    'sort' => $imagesData[$index]['sort'],
                    'created_at'  => Carbon::now()->toDateString(),
                    'updated_at' => Carbon::now()->toDateString(),
                ];
            }
        }


        if (ProductImage::insert($data)) {
            return $this;
        }
        throw new Exception('Error while storing product images');
    }
    public function storePricesForVariations($request, $childrenIds)
    {
        $data = [];
        foreach ($request->product_variations as $variation) {
            $pricesInfo = $variation['isSamePriceAsParent'] ? $request->prices : ($variation['prices'] ?? []);
        }
        if (is_null($pricesInfo)) {
            return $this;
        }
        $childrenIdsArray = $childrenIds;
        $data = [];
        foreach ($childrenIdsArray as $key => $child) {
            foreach ($pricesInfo as $key => $price) {
                $data[] = [
                    'product_id' => $child,
                    'price_id' => $price['price_id'],
                    'price' => $price['price_id'],
                    'discounted_price' => $price['discounted_price'],
                    'created_at' =>  Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
            }
        }
        ProductPrice::Insert($data);
    }
    public function storeVariations($request, $product)
    {
        DB::beginTransaction();
        try {

        throw_if(!$request->product_variations, Exception::class, 'No variations found');

        $productVariationParentsArray = [];
        $imagesDeletedArray = [];
        $imagesArray = [];
        $fieldsArray = [];
        $attributesArray = [];
        foreach ($request->product_variations as $variation) {
            $imagePath = array_key_exists('image', $variation) ? $variation['image'] : "";
            if (!is_null($variation['image'])) {
                if ($variation['image'] && !is_string($variation['image'])) {
                    $imagePath = uploadImage($variation['image'],  config('images_paths.product.images'));
                } else {
                    $imagePath = "";
                }
            }
            $productVariationsArray = [
                'name' => json_encode($request->name),
                'code' => $variation['code'],
                'type' => 'variable_child',
                'sku' => array_key_exists('sku', $variation) ? $variation['sku'] : null,
                'quantity' => $variation['quantity'],
                'reserved_quantity' => $variation['reserved_quantity'],
                'minimum_quantity' => $variation['minimum_quantity'],
                'height' => array_key_exists('height', $variation) ? $variation['height'] : null,
                'width' => array_key_exists('width', $variation) ? $variation['width'] : null,
                'length' => array_key_exists('p_length', $variation) ? $variation['p_length'] : null,
                'weight' => array_key_exists('weight', $variation) ? $variation['weight'] : null,
                'barcode' => array_key_exists('barcode', $variation) ? $variation['barcode'] : null,
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id ?? null,
                'tax_id' => $request->tax_id ?? null,
                'brand_id' => $request->brand_id ?? null,
                'summary' => json_encode($request->summary) ?? null,
                'specification' => json_encode($request->specification) ?? null,
                'meta_title' => json_encode($request->meta_title) ?? null,
                'meta_keyword' => json_encode($request->meta_keyword) ?? null,
                'meta_description' => json_encode($request->meta_description) ?? null,
                'description' => json_encode($request->description) ?? null,
                'website_status' => $request->website_status,
                'parent_product_id' => $product->id,
                'products_statuses_id' =>  array_key_exists('products_statuses_id', $variation) ? $variation['products_statuses_id'] : null,
                'image' => $imagePath,
                'is_show_related_product' => $variation['is_show_related_product'] ?? null,
                'bundle_reserved_quantity' => null,
                'pre_order' => array_key_exists('pre_order', $variation) ? $variation['pre_order'] : null,


            ];

            $imagesDeletedArray = array_key_exists('images_deleted', $variation) ?  $variation['images_deleted'] : [];
            $imagesArray = array_key_exists('images', $variation) ? $variation['images'] : [];
            $imagesData = array_key_exists('images_data', $variation) ? $variation['images_data'] : [];
            $fieldsArray =array_key_exists('fields', $variation) ? $variation['fields'] : [];
            $attributesArray = array_key_exists('attributes', $variation) ? $variation['attributes'] : [];
            $productVariationParentsArray[] = $productVariationsArray;
        }
        $model = new Product();
        $productVariation = Product::upsert($productVariationParentsArray, 'id', $model->getFillable());
        $childrenIds = [];
        if ($productVariation) {

            $childrenData = Product::where('parent_product_id', $product->id)->get();
            foreach ($childrenData as $key => $child) {
                $childrenIds[$key] = $child->id;
            }

            $this->removeImagesForVariations($imagesDeletedArray, $childrenIds);
            $this->storeImagesForVariations($imagesArray, $imagesData, $childrenIds);
            $this->storePricesForVariations($request, $childrenIds);
            $this->storeFieldsForVariations($fieldsArray, $childrenIds);
            $this->storeAttributesForVariations($attributesArray, $childrenIds);
        }

        return $childrenIds;

            DB::commit();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // END OF TYPE VARIABLE
    public function createAndUpdateProduct($request, $product)
    {
        // DB::beginTransaction();
        // try {
        //$request=(object)$request;
        $product = $product ?  $product : new Product();
        $product->name = ($request->name);
        $product->slug = $request->slug;
        $product->code = $request->code;
        $product->sku = $request->sku ?? null;
        $product->type = $request->type;
        $product->quantity = 0;
        if (!$product->type == 'bundle') {
            $diffrenceQuantity = $request->quantity - ($product->reserved_quantity + $product->bundle_reserved_quantity);
            if ($diffrenceQuantity > ($request->reserved_quantity - $product->reserved_quantity)) {
                $product->reserved_quantity = $request->reserved_quantity;
            }
        } else {
            $product->reserved_quantity = $request->reserved_quantity;
        }
        $product->minimum_quantity = $request->minimum_quantity;
        $product->summary = ($request->summary) ?? null;
        $product->specification = ($request->specification) ?? null;

        $product->meta_title = $request->meta_title ?? null;
        $product->meta_keyword = $request->meta_keyword ?? null;
        $product->meta_description = $request->meta_description ?? null;
        $product->description = $request->description ?? null;
        $product->website_status = $request->website_status;
        $product->barcode = $request->barcode ?? null;
        $product->height = $request->height ?? null;
        $product->width = $request->width ?? null;
        $product->is_disabled = 0;
        $product->length = $request->p_length ?? null;
        $product->weight = $request->weight ?? null;
        $product->is_default_child = $request->is_default_child ?? 0;
        $product->parent_product_id = $request->parent_product_id ?? null;
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id ?? null;
        $product->brand_id = $request->brand_id ?? null;
        $product->tax_id = $request->tax_id ?? null;
        $product->products_statuses_id = $request->products_statuses_id;
        $product->is_show_related_product = $request->is_show_related_product ?? null;
        $product->pre_order = $request->pre_order ?? null;
        $product->bundle_reserved_quantity = null;

        if ($request->file('image') && !is_string($request->file('image')))
            $product->image = uploadImage($request->image, config('images_paths.product.images'));

        $product->save();

        // DB::commit();
        return $product;
        // } catch (Exception $e) {
        // DB::rollBack();
        // throw new Exception($e->getMessage());
        // }
    }
}
