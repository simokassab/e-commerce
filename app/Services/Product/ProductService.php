<?php

namespace App\Services\Product;

use App\Http\Requests\Product\StoreProductRequest;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductField;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductLabel;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductRelated;
use App\Models\Product\ProductTag;
use App\Models\RolesAndPermissions\CustomPermission;
use App\Services\Category\CategoryService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

use function PHPUnit\Framework\isEmpty;

class ProductService
{
    // private $request, $product_id;

    public function storeAdditionalProductData(Request $request, $productId, $childrenIds)
    {

        // $request = $request;
        // $productId = $product_id;
        // $childrenIds = $childrenIds ?? [];

        //  $this->storeAdditionalCategrories($request, $productId, $childrenIds)
//         $this->storeAdditionalFields($request, $productId, $childrenIds) // different than parent
            // ->storeAdditionalFields($request, $productId, $childrenIds) // different than parent
            $this->storeAdditionalImages($request, $productId, $childrenIds);// different than parent
            // ->storeAdditionalLabels($request, $productId, $childrenIds)
            // ->storeAdditionalTags($request, $productId, $childrenIds)
            // ->storeAdditionalPrices($request, $productId, $childrenIds);

    }



    public function storeAdditionalCategrories(Request $request, $productId, $childrenIds)
    {
        if (!$request->has('categories'))
            return $this;

        $categoriesIdsArray = [];
        $oneLevelCategoryArray = CategoryService::loopOverMultiDimentionArray($request->categories);
        foreach ($oneLevelCategoryArray as $key => $category) {
            if ($category['checked']) {
                $categoriesIdsArray[$key]['category_id'] = $category['id'];
                $categoriesIdsArray[$key]['product_id'] = $productId;
            }
        }
        if (ProductCategory::insert($categoriesIdsArray))
            return $this;

        throw new Exception('Error while storing product images');
    }

    public function storeAdditionalFields(Request $request, $productId, $childrenIds)
    {
        if (!$request->has('fields'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $productId;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->fields as $index => $field) {
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


    /**
     * @throws \App\Exceptions\FileErrorException
     * @throws Exception
     */
    public function storeAdditionalImages(Request $request, $productId, $childrenIds)
    {
        // if (!$request->has('images'))
        //     return $this;

         if (count($request->images) != count($request->images_data)){
             throw new Exception('Images and images_data count is not equal');
         }

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $productId;
        $data = [];
        foreach ($childrenIdsArray as $key => $child) {
            foreach ($request->images as $index => $image) {

                $imagePath = uploadImage($image, config('images_paths.product.images'));
                ProductImage::create([
                    'product_id' => $child,
                    'image' => $imagePath,
                    'title' => ($request->images_data[$index]['title']),
                    'sort' => $request->images_data[$index]['sort'],
                    'created_at'  => Carbon::now()->toDateString(),
                    'updated_at' => Carbon::now()->toDateString(),
                ]);
            }
        }

            // return $data;
           if (ProductImage::insert($data)) {
               return $this;
           }

            // return $this;
        throw new Exception('Error while storing product images');
    }

    public function storeAdditionalLabels(Request $request,$productId,$childrenIds)
    {
        if (!$request->has('labels'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $productId;

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

    public function storeAdditionalTags(Request $request,$productId,$childrenIds)
    {
        if (!$request->has('tags'))
            return $this;

        $childrenIdsArray = $childrenIds;
        $childrenIdsArray[] = $productId;

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

    public function storeAdditionalBundle(Request $request, Product $product)
    {
        if ($request->type == 'bundle') {
            $relatedProductsArray = $request->related_products ?? [];
            foreach ($request->related_products as $related_product => $value) {
                $relatedProductsArray[$related_product]["parent_product_id"] = $product->id;
                $relatedProductsArray[$related_product]["created_at"] = Carbon::now()->toDateTimeString();
                $relatedProductsArray[$related_product]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductRelated::insert($relatedProductsArray);
        }

        return $this;
    }

    public function storeAdditionalPrices(Request $request,$productId,$childrenIds)
    {

        if ($request->has('prices')) {
            $pricesArray =  [];
            foreach ($request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $productId;
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

    public function storeVariationsAndPrices(Request $request, $product)
    {

        try {
            $childrenIds = [];
            $data = [];
            throw_if(!$request->product_variations, Exception::class, 'No variations found');

            foreach ($request->product_variations as $variation) {
                if ($variation['image'] == null)
                    $imagePath = "";
                else {
                    $imagePath = uploadImage($variation['image'],  config('images_paths.product.images'));
                }
                $productVariationsArray = [
                    'name' => json_encode($request->name),
                    'slug' => $variation['slug'],
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
                    'meta_title' => json_encode($request->meta_title),
                    'meta_description' => json_encode($request->meta_description),
                    'description' => json_encode($request->description),
                    'status' => $request->status,
                    'parent_product_id' => $product->id,
                    'products_statuses_id' => $request->products_statuses_id,
                    'image' => $imagePath,

                ];


                $productVariation = Product::create($productVariationsArray);

                $pricesInfo = $variation['isSamePriceAsParent'] ? $request->prices : $variation['prices'];
                foreach ($pricesInfo as $key => $price) {
                    $pricesInfo[$key]['product_id'] = $productVariation->id;
                }

                $childrenIds[] = $productVariation->id;

                $data[] = $pricesInfo;
            }
            $finalPricesCollect = collect($data)->collapse()->toArray();
            ProductPrice::insert($finalPricesCollect);


            if (count($childrenIds) > 0) {
                return $childrenIds;
            }

            throw new Exception('No variations found');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function createProduct(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->code = $request->code;
            $product->sku = $request->sku;
            $product->type = $request->type;
            $product->quantity = $request->quantity;
            $product->reserved_quantity = $request['reserved_quantity'] ?? 0;
            $product->minimum_quantity = $request->minimum_quantity;
            $product->summary = $request['summary'] ?? null;
            $product->specification = $request['specification'] ?? null;
            if ($request->has('image') && !empty($request->image))
                $product->image = uploadImage($request->image, config('images_paths.product.images'));

            $product->meta_title = $request['meta_title'] ?? null;
            $product->meta_description = $request['meta_description'] ?? null;
            $product->meta_keyword = $request['meta_keyword'] ?? null;
            $product->description = $request['description'] ?? null;
            $product->status = $request->status;
            $product->barcode = $request['barcode'] ?? null;
            $product->height = $request['height'] ?? null;
            $product->width = $request['width'] ?? null;
            $product->is_disabled = 0;
            $product->length = $request['p_length'] ?? null;
            $product->weight = $request['weight'] ?? null;
            $product->is_default_child = $request->is_default_child ?? 0;
            $product->parent_product_id = $request['parent_product_id'] ?? null;
            $product->category_id = $request->category_id;
            $product->unit_id = $request->unit_id;
            $product->brand_id = $request['brand_id'] ?? null;
            $product->tax_id = $request['tax_id'] ?? null;
            $product->products_statuses_id = $request->products_statuses_id;
            $product->is_show_related_product = $request['is_show_related_product'] ?? 0;
            $product->save();
            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


}
