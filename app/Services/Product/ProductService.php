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
        $fieldCheck = ProductField::where('product_id', $product->id)->delete();

        if (!$request->has('fields'))
            return $this;


        $data = [];

        foreach ($request->fields as $index => $field) {
            if (gettype($field) == 'string') {
                $field = (array)json_decode($field);
            }
            if ($field["type"] == 'select') {
                $data[$index]["value"] = null;
                if (gettype($field["value"]) == 'array') {
                    $data[$index]["field_value_id"] = $field["value"][0];
                } elseif (gettype($field["value"]) == 'integer') {
                    $data[$index]["field_value_id"] = $field["value"];
                }
            } else {
                $data[$index]["value"] = ($field['value']);
                $data[$index]["field_value_id"] = null;
                if (gettype($field['value']) == 'array') {
                    $data[$index]["value"] = ($field['value']);
                }
            }
            $data[$index]["product_id"] = $product->id;
            $data[$index]["field_id"] = $field['field_id'];
        }
        if (ProductField::insert($data)) {
            return $this;
        }

        throw new Exception('Error while storing product fields');
    }

    public function storeAdditionalAttributes($request, $product)
    {
        $fieldCheck = ProductField::where('product_id', $product->id)->delete();

        if (!$request->has('attributes'))
            return $this;

        $data = [];
        foreach ($request->attributes as $index => $attribute) {
            if (gettype($attribute) == 'string') {
                $attribute = (array)json_decode($attribute);
            }
            if ($attribute["type"] == 'select') {
                $data[$index]["value"] = null;
                if (gettype($attribute["value"]) == 'array') {
                    $data[$index]["field_value_id"] = $attribute["value"][0];
                } elseif (gettype($attribute["value"]) == 'integer') {
                    $data[$index]["field_value_id"] = $attribute["value"];
                }
            } else {
                $data[$index]["value"] = ($attribute['value']);
                $data[$index]["field_value_id"] = null;
                if (gettype($attribute['value']) == 'array') {
                    $data[$index]["value"] = ($attribute['value']);
                }
            }
            $data[$index]["product_id"] = $product->id;
            $data[$index]["field_id"] = $attribute['field_id'];
        }
        if (ProductField::insert($data)) {

            return $this;
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
                    'name' => json_encode($value['name']) ?? "",
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
                $pricesArray[$price]["discounted_price"] = $value['discounted_price'];
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
        $fieldCheck = ProductField::whereIn('product_id', $childrenIds)->delete();

        if (is_null($fieldsArray))
        return $this;

        $data = [];

        foreach ($childrenIds as $key => $child) {
            foreach ($fieldsArray[$key] as $index => $field) {
                if (gettype($field) == 'string') {
                    $field = (array)json_decode($field);
                }
                if ($field["type"] == 'select') {
                    $data[$key]["value"] = null;
                    if (gettype($field["value"]) == 'array') {
                        $data[$key]["field_value_id"] = $field["value"][0];
                    } elseif (gettype($field["value"]) == 'integer') {
                        $data[$key]["field_value_id"] = $field["value"];
                    }
                } else {
                    $data[$key]["value"] = ($field['value']);
                    $data[$key]["field_value_id"] = null;
                    if (gettype($field['value']) == 'array') {
                        $data[$key]["value"] = ($field['value']);
                    }
                }
                $data[$key]["product_id"] = $child;
                $data[$key]["field_id"] = $field['field_id'];
            }
        }
        if (ProductField::insert($data)) {

            return $this;
        }

        throw new Exception('Error while storing product fields');
    }

    public function storeAttributesForVariations($attributesArray, $childrenIds)
    {
        $attributesCheck = ProductField::whereIn('product_id', $childrenIds)->delete();

        if (is_null($attributesArray))
            return $this;

        $data = [];

        foreach ($childrenIds as $key => $child) {
            foreach ($attributesArray as $index => $attribute) {
                if (gettype($attribute) == 'string') {
                    $attribute = (array)json_decode($attribute);
                }
                if ($attribute["type"] == 'select') {
                    $data[$key]["value"] = null;
                    if (gettype($attribute["value"]) == 'array') {
                        $data[$key]["field_value_id"] = $attribute["value"][0];
                    } elseif (gettype($attribute["value"]) == 'integer') {
                        $data[$key]["field_value_id"] = $attribute["value"];
                    }
                } else {
                    $data[$key]["value"] = ($attribute['value']);
                    $data[$key]["field_value_id"] = null;
                    if (gettype($attribute['value']) == 'array') {
                        $data[$key]["value"] = ($attribute['value']);
                    }
                }
                $data[$key]["product_id"] = $child;
                $data[$key]["field_id"] = $attribute['field_id'];
            }
        }
        if (ProductField::insert($data)) {
            return $this;
        }

        throw new Exception('Error while storing product attributes for variations');
    }

    public function removeImagesForVariations($imagesDeletedArray, $childrenIds)
    {
        if (is_null($imagesDeletedArray))
            return $this;
        // if (!$request->has('product_variations'))
        //     return $this;

        // if ($request->product_varitations == null) {
        //     return $this;
        // }

        // if (!Arr::has($request->product_varitations->toArray(), 'product_varitations.*.images_deleted'))
        //     return $this;
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
        // DB::beginTransaction();
        // try {

        throw_if(!$request->product_variations, Exception::class, 'No variations found');

        $productVariationParentsArray = [];
        $imagesDeletedArray = [];
        $imagesArray = [];
        $fieldsArray = [];
        $attributesArray = [];
        foreach ($request->product_variations as $variation) {
            $imagePath = array_key_exists('image',$variation) ? $variation['image'] : "";
            if(!is_null($variation['image'])){
                dd($variation['image']);
                if ($variation['image']->file('image') && !is_string($variation['image']->file('image'))){
                    $imagePath = uploadImage($variation['image'],  config('images_paths.product.images'));
            }
            else{
                 $imagePath="";
            }
            }
            $productVariationsArray = [
                'name' => json_encode($request->name),
                'code' => $variation['code'],
                'type' => 'variable_child',
                'sku' => $variation['sku'],
                'quantity' => $variation['quantity'],
                'reserved_quantity' => $variation['reserved_quantity'],
                'minimum_quantity' => $variation['minimum_quantity'],
                'height' => $variation['height'],
                'width' => $variation['width'],
                'length' => $variation['p_length'],
                'weight' => $variation['weight'],
                'barcode' => $variation['barcode'],
                'category_id' => $request->category_id,
                'unit_id' => $request->unit_id,
                'tax_id' => $request->tax_id,
                'brand_id' => $request->brand_id,
                'summary' => json_encode($request->summary),
                'specification' => json_encode($request->specification),
                'meta_title' => json_encode($request->meta_title) ?? "",
                'meta_keyword' => json_encode($request->meta_keyword) ?? "",
                'meta_description' => json_encode($request->meta_description) ?? "",
                'description' => json_encode($request->description) ?? "",
                'website_status' => $request->website_status,
                'parent_product_id' => $product->id,
                'products_statuses_id' => $variation['products_statuses_id'],
                'image' => $imagePath,
                'is_show_related_product' => $variation['is_show_related_product'] ?? 0,
                'bundle_reserved_quantity' => null,
                'pre_order' => $variation['pre_order'] ?? 0,


            ];

            $imagesDeletedArray = array_key_exists('images_deleted', $variation) ?  $variation['images_deleted'] : [];
            $imagesArray = array_key_exists('images', $variation) ? $variation['images'] : [];
            $imagesData = array_key_exists('images_data', $variation) ? $variation['images_data'] : [];
            // $fieldsArray =$variation['fields'];
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
            // $this->storeFieldsForVariations($fields, $childrenIds);
            $this->storeAttributesForVariations($attributesArray, $childrenIds);
        }

        if (count($childrenIds) > 0) {
            return $childrenIds;
        }

        //     DB::commit();
        // } catch (Exception $e) {
        //     throw new Exception($e->getMessage());
        // }
    }
    // END OF TYPE VARIABLE

    public function createAndUpdateProduct($request, $product = null)
    {
        // DB::beginTransaction();
        // try {
        //$request=(object)$request;
        $product = $product ?  $product : new Product();
        $product->name = ($request->name);
        $product->slug = $request->slug;
        $product->code = $request->code;
        $product->sku = $request->sku;
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
        $product->summary = ($request->summary);
        $product->specification = ($request->specification);

        $product->meta_title = $request->meta_title ?? null;
        $product->meta_keyword = $request->meta_keyword ?? null;
        $product->meta_description = $request->meta_description ?? null;
        $product->description = $request->description ?? null;
        $product->website_status = $request->website_status;
        $product->barcode = $request->barcode;
        $product->height = $request->height;
        $product->width = $request->width;
        $product->is_disabled = 0;
        $product->length = $request->p_length;
        $product->weight = $request->weight;
        $product->is_default_child = $request->is_default_child ?? 0;
        $product->parent_product_id = $request->parent_product_id ?? null;
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->brand_id = $request->brand_id;
        $product->tax_id = $request->tax_id;
        $product->products_statuses_id = $request->products_statuses_id;
        $product->is_show_related_product = $request->is_show_related_product ?? 0;
        $product->pre_order = $request->pre_order ?? 0;
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
