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
use Illuminate\Support\Facades\DB;

class ProductService
{

    public function storeAdditionalProductData($request, $product, $childrenIds)
    {

        //$request=(object)$request;

        $this->storeAdditionalCategrories($request, $product, $childrenIds)
            ->storeAdditionalFields($request, $product) // different than parent
            ->storeAdditionalImages($request, $product) // different than parent
            ->storeAdditionalLabels($request, $product, $childrenIds)
            ->storeAdditionalTags($request, $product, $childrenIds)
            ->storeAdditionalPrices($request, $product, $childrenIds)
            ->storeAdditionalAttributes($request, $product);
    }

    public function storeAdditionalCategrories($request, $product, $childrenIds)
    {
        //$request=(object)$request;
        $categoryCheck = ProductCategory::where('product_id', $product->id)->orWhereIn('product_id', $childrenIds)->delete();
        // if ($categoryCheck) {
        //     $categoryCheck->destroy();
        // }

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $product->id;

        if (!$request->has('categories'))
            return $this;

        $categoriesIdsArray = [];
        $oneLevelCategoryArray = CategoryService::loopOverMultiDimentionArray($request->categories);
        foreach ($childrenIdsArray as $key => $child) {
            foreach ($oneLevelCategoryArray as $key => $category) {
                if ($category['checked']) {
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

    public function storeAdditionalImages($request, $product)
    {
        //$request=(object)$request;
        $imageCheck = ProductImage::where('product_id', $product->id)->delete();

        if (!$request->has('images') || is_null($request->images)) {
            return $this;
        }

        // if (count($request->images) != $request->images_data->count()) {
        //     throw new Exception('Images and images_data count is not equal');
        // }

        $data = [];
        foreach ($request->images as $index => $image) {
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
            // $this->calculateBundleReservedQuantities($request);
            // $this->calculateReservedQuantity($request, $product);
        }
        return $this;
    }

    public function canMakeBundle($request)
    {

        $bundleIds = [];
        $bundleQuantities = [];
        $quantities = [];
        foreach ($request->related_products as $related_product => $value) {
            //TODO collection and pluck
            $bundleIds[$related_product] = $value['child_product_id'];
            $bundleQuantities[$related_product] = $value['child_quantity'];
        }
        $bundleProductsQuantities = Product::findMany($bundleIds)->pluck('quantity');

        foreach ($bundleProductsQuantities->toArray() as $key => $bundleProductQuantity) {
            if (!($bundleQuantities[$key] <= $bundleProductQuantity)) {
                return errorResponse('Bundle quantity is greater than product quantity');
            }
            $quantities[$key]['quantity'] = $bundleProductQuantity / $bundleQuantities[$key];
        }

        $minimumBundleQuantityInArray = array_column($quantities, 'quantity');
        $minimumBundleQuantity = min($minimumBundleQuantityInArray);
        if ($minimumBundleQuantity < $request->quantity) {
            return errorResponse('Minimum quantity in bundle is ' . $minimumBundleQuantity);
        }
        return $minimumBundleQuantity;
    }
    public function calculateBundleReservedQuantities($request)
    {
        DB::beginTransaction();
        try{
        $canMakeBundle = $this->canMakeBundle($request);
        $bundleReservedQuantity = [];
        foreach ($request->related_products as $related_product => $value) {
            $bundleReservedQuantity[$related_product]['id'] = $value['child_product_id'];
            $bundleReservedQuantity[$related_product]['bundle_reserved_quantity'] = $value['child_quantity'] * $request->quantity;
        }

        batch()->update(new Product, $bundleReservedQuantity, 'id');
        DB::commit();
    }catch(Exception $e){
        DB::rollBack();
        throw new Exception($e->getMessage());
    }

    }

    public function calculateReservedQuantity($request, $product)
    {
        DB::beginTransaction();
        try {
            $minimumBundleQuantity = $this->canMakeBundle($request);
            $reservedQuantity = ($minimumBundleQuantity - $request->quantity);
            $product->reserved_quantity = $reservedQuantity;
            $product->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
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
    public function storeFieldsForVariations($request, $childrenIds)
    {
        $fieldCheck = ProductField::whereIn('product_id', $childrenIds)->delete();

        throw_if(!$request->product_variations, Exception::class, 'No variations found');

        if (!$request->has('product_variations'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->product_variations[$key]['fields'] as $index => $field) {
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

    public function storeAttributesForVariations($request, $childrenIds)
    {
        $fieldCheck = ProductField::whereIn('product_id', $childrenIds)->delete();

        throw_if(!$request->product_variations, Exception::class, 'No variations found');

        if (!$request->has('product_variations'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->product_variations[$key]['attributes'] as $index => $attribute) {
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

    public function storeImagesForVariations($request, $childrenIds)
    {
        $imageCheck = ProductImage::whereIn('product_id', $childrenIds)->delete();

        throw_if(!$request->product_variations, Exception::class, 'No variations found');

        if (!$request->has('product_variations'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->product_variations[$key]['images'] as $index => $image) {
                $imagePath = uploadImage($image, config('images_paths.product.images'));
                $data[] = [
                    'product_id' => $child,
                    'image' => $imagePath,
                    'title' => json_encode($request->product_variations[$key]['images_data'][$index]['title']),
                    'sort' => $request->product_variations[$key]['images_data'][$index]['sort'],
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
            $pricesInfo = $variation['isSamePriceAsParent'] ? $request->prices : $variation['prices'];

            foreach ($pricesInfo as $key => $price) {
                $data[$key]['product_id'] = $childrenIds[$key];
                $data[$key]['price_id'] = $price['price_id'];
                $data[$key]['price'] = $price['price'];
                $data[$key]['discounted_price'] = $price['discounted_price'];
                $data[$key]['created_at'] = Carbon::now()->toDateTimeString();
                $data[$key]['updated_at'] = Carbon::now()->toDateTimeString();
            }
        }
        ProductPrice::Insert($data);
    }

    public function storeVariations($request, $product)
    {
        DB::beginTransaction();
        try {

            $childrenIds = [];

            throw_if(!$request->product_variations, Exception::class, 'No variations found');

            $productVariationParentsArray = [];
            foreach ($request->product_variations as $variation) {
                if ($variation['image'] == null)
                    $imagePath = "";
                else
                    $imagePath = uploadImage($variation['image'],  config('images_paths.product.images'));

                $productVariationsArray = [
                    'name' => ($request->name),
                    'code' => $variation['code'],
                    'type' => 'variable_child',
                    'sku' => $variation['sku'],
                    'quantity' => $variation['quantity'],
                    'reserved_quantity' => 0,
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
                    'summary' => ($request->summary),
                    'specification' => ($request->specification),
                    'meta_title' => ($request->meta_title) ?? "",
                    'meta_keyword' => ($request->meta_keyword) ?? "",
                    'meta_description' => ($request->meta_description) ?? "",
                    'description' => ($request->description) ?? "",
                    'website_status' => $request->status,
                    'parent_product_id' => $product->id,
                    'products_statuses_id' => $variation['products_statuses_id'],
                    'image' => $imagePath,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                    'bundle_reserved_quantity' => null,
                    'pre_order' => 0,

                ];
                $productVariationParentsArray[] = $productVariationsArray;

                // $productVariation = Product::updateOrCreate($productVariationsArray,['id' => $variation['id']]);

            }

            $productVariation = Product::upsert($productVariationParentsArray, ['id'], Product::$fillable);

            foreach ($productVariation as $key => $variation) {
                $childrenIds[] = $productVariation->id;
            }

            $this->storeImagesForVariations($request, $childrenIds);
            $this->storePricesForVariations($request, $childrenIds);
            $this->storeFieldsForVariations($request, $childrenIds);
            $this->storeAttributesForVariations($request, $childrenIds);

            if (count($childrenIds) > 0) {
                return $childrenIds;
            }

            DB::commit();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    // END OF TYPE VARIABLE

    public function createAndUpdateProduct($request, $product = null)
    {
        DB::beginTransaction();
        try {
            //$request=(object)$request;
            $product = $product ?  $product : new Product();
            $product->name = ($request->name);
            $product->slug = $request->slug;
            $product->code = $request->code;
            $product->sku = $request->sku;
            $product->type = $request->type;
            $product->quantity = $request->quantity;
            $product->reserved_quantity = null;
            $product->minimum_quantity = $request->minimum_quantity;
            $product->summary = ($request->summary);
            $product->specification = ($request->specification);
            if ($request->image)
                $product->image = uploadImage($request->image, config('images_paths.product.images'));

            $product->meta_title = $request->meta_title ?? null;
            $product->meta_keyword = $request->meta_keyword ?? null;
            $product->meta_description = $request->meta_description ?? null;
            $product->description = $request->description ?? null;
            $product->website_status = $request->status;
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
            $product->pre_order = 0;
            $product->bundle_reserved_quantity = null;
            $product->save();
            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


}
