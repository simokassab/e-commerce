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
    private $request, $product_id;

    public function storeAdditionalProductData(Request $request, $product_id, $childrenIds)
    {

        $this->request = $request;
        $this->product_id = $product_id;
        $this->childrenIds = $childrenIds ?? [];

        self::storeAdditionalCategrories()
        // self::storeAdditionalFields() // different than parent
            ->storeAdditionalFields() // different than parent
            ->storeAdditionalImages() // different than parent
            ->storeAdditionalLabels()
            ->storeAdditionalTags()
            ->storeAdditionalPrices();
    }

    // private function storeAdditionalCategrories()
    // {
    //     if (!$this->request->has('categories'))
    //         return $this;

    //     $childrenIdsArray = $this->childrenIds;
    //     $childrenIdsArray[] = $this->product_id;

    //     $data = [];

    //     foreach ($childrenIdsArray as $key => $child) {
    //         foreach ($this->request->categories as $index => $category) {
    //             $data[] = [
    //                 'product_id' => $child,
    //                 'category_id' => $category,
    //                 'created_at' => Carbon::now()->toDateTimeString(),
    //                 'updated_at' => Carbon::now()->toDateTimeString()
    //             ];
    //         }
    //     }

    //     if (ProductCategory::insert($data)) {
    //         return $this;
    //     }

    //     throw new Exception('Error while storing product categories');
    // }
    private function storeAdditionalCategrories()
    {
        if(!$this->request->has('categories'))
            return $this;

        $categoriesIdsArray = [];
        $oneLevelCategoryArray = CategoryService::loopOverMultiDimentionArray($this->request->categories);
        foreach ($oneLevelCategoryArray as $key => $category) {
            if ($category['checked']) {
                $categoriesIdsArray[] = $category['id'];
                $categoriesIdsArra[] = $this->product_id;
            }
        }
        if (ProductCategory::insert($categoriesIdsArray))
            return $this;

        throw new Exception('Error while storing product images');
    }

    private function storeAdditionalFields()
    {
        if (!$this->request->has('fields'))
            return $this;

        $childrenIdsArray = $this->childrenIds;
        $childrenIdsArray[] = $this->product_id;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($this->request->fields as $index => $field) {
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

    // if ($this->request->has('fields')) {
    //     $fieldsArray = $this->request->fields ?? [];

    //     $data = collect($this->request->fields);
    //     $data->each(function ($item, $key) {
    //         $item['product_id'] = $this->product_id;
    //         $item['created_at'] = Carbon::now()->toDateTimeString();
    //         $item['updated_at'] = Carbon::now()->toDateTimeString();
    //     });


    //     foreach ($this->request->fields as $field => $value) {

    //         if ($fieldsArray[$field]["type"] == 'select')
    //             $fieldsArray[$field]["value"] =  null;
    //         else {
    //             $fieldsArray[$field]["value"] = json_encode($value['value']);
    //             $fieldsArray[$field]["field_value_id"] = null;
    //         }

    //         $fieldsArray[$field]["product_id"] = $this->product_id;
    //         $fieldsArray[$field]["created_at"] = Carbon::now()->toDateTimeString();
    //         $fieldsArray[$field]["updated_at"] = Carbon::now()->toDateTimeString();
    //         unset($fieldsArray[$field]['type']);
    //     }
    //     ProductField::insert($fieldsArray);
    // }

    // return $this;
    // }

    private function storeAdditionalImages()
    {
        if (!$this->request->has('images'))
            return $this;

        // if ($this->request->image->count() != $this->request->images_data->count())
        //     throw new Exception('Images and images_data count is not equal');

        $childrenIdsArray = $this->childrenIds;
        $childrenIdsArray[] = $this->product_id;

        $data = [];
        foreach ($childrenIdsArray as $key => $child) {
            foreach ($this->request->images as $index => $image) {
                $imagePath = uploadImage($image['image'], config('images_paths.product.images'));

                $data[] = [
                    'product_id' => $child,
                    'image' => $imagePath,
                    'title' => ($this->request->images_data[$index]['title']),
                    'sort' => $this->request->images_data[$index]['sort'],
                    'created_at'  => today()->toDateString(),
                    'updated_at' => today()->toDateString(),
                ];
            }
        }
        if (ProductImage::insert($data)) {
            return $this;
        }

        throw new Exception('Error while storing product images');
    }

    private function storeAdditionalLabels()
    {
        if (!$this->request->has('labels'))
            return $this;

        $childrenIdsArray = $this->childrenIds;
        $childrenIdsArray[] = $this->product_id;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($this->request->labels as $index => $label) {
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

    private function storeAdditionalTags()
    {
        if (!$this->request->has('tags'))
            return $this;

        $childrenIdsArray = $this->childrenIds;
        $childrenIdsArray[] = $this->product_id;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($this->request->tags as $index => $tag) {
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

    private function storeAdditionalPrices()
    {

        if ($this->request->has('prices')) {
            $pricesArray =  [];
            foreach ($this->request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $this->product_id;
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
        // try {
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
        // } catch (Exception $e) {

        //     throw new Exception($e->getMessage());
        // }

        return $product;
    }

    public static function getAllCategoriesNested($categories)
    {
        $rootCategories = self::getRootCategories($categories);
        $lastResult = [];
        foreach ($rootCategories as $rootCategory) {
            $result = (object)[];
            $result->id = $rootCategory->id;
            $result->label = $rootCategory->name;
            $result->expanded = true;
            $nodes = self::getCategoryChildren($rootCategory, $categories);
            $nodesArray = [];

            if (is_array($nodes) && count($nodes) > 0) {
                foreach ($nodes as $node) {
                    $nodesArray[] = $node;
                }
            }

            $result->nodes = $nodesArray;

            $result = (array)$result;
            $lastResult[] = $result;
        }
        return $lastResult;
    }

    private static function getRootCategories($categories)
    {
        $arrayOfParents = [];
        $arrayOfParentsCodes = [];

        foreach ($categories as $category) {
            if (!is_null($category->parent_id)) {
                continue;
            }
            if (is_null($category->parent_id)) {
                $arrayOfParents[] = $category;
            }
        }

        return ($arrayOfParents);
    }

    private static function getCategoryChildren(int | Category $category, $allCategories)
    {

        $categoriesChildren = self::generateChildrenForAllCategories($allCategories);
        $categoryId = (is_numeric($category) ? $category : $category->id);

        return self::drawCategoryChildren($categoryId, $categoriesChildren, true, $allCategories);
    }

    private static function drawCategoryChildren($parentCategoryId, $allCategoryIDs, $isMultiLevel = false, $allCategories): array
    {
        //with levels
        $childCategory = array();
        if (empty($allCategoryIDs[$parentCategoryId])) {
            return [];
        }
        foreach ($allCategoryIDs[$parentCategoryId] as $categoryID) {

            $categoryID =  is_numeric($categoryID) ? ($categoryID) : $categoryID->id;

            if ($isMultiLevel) {
                $childCategory[$categoryID] = [
                    'id' => $allCategories->find($categoryID)->id,
                    'label' => $allCategories->find($categoryID)->name,
                    'nodes' => [],
                ];
                $childCategory[$categoryID]['nodes'] = self::drawCategoryChildren($categoryID, $allCategoryIDs, $isMultiLevel, $allCategories);
            } else {
                $childCategory[] = $categoryID;
                $childCategory = array_merge($childCategory, self::drawCategoryChildren($categoryID, $allCategoryIDs, $isMultiLevel, $allCategories));
            }
        }
        return $childCategory;
    }


    private static function generateChildrenForAllCategories($allCategories)
    {
        $categoryChildren = [];
        foreach ($allCategories as $currentCategory) {
            $parentId = ($currentCategory->parent_id ?? 0);

            if (!isset($categoryChildren[$parentId])) {
                $categoryChildren[$parentId] = [];
            }
            $categoryChildren[$parentId][] = Category::find($currentCategory->id);
        }


        return $categoryChildren;
    }
}
