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
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public $request, $product_id;

    public function storeAdditionalProductData(Request $request, $product_id, $childrenIds)
    {

        $this->request = $request;
        $this->product_id = $product_id;
        $this->childrenIds = $childrenIds ?? [];

        self::storeAdditionalCategrories()
            // ->storeAdditionalFields() // different than parent
            // ->storeAdditionalImages() // different than parent
            ->storeAdditionalLabels()
            ->storeAdditionalTags()
            ->storeAdditionalPrices();
    }

    private function storeAdditionalCategrories()
    {
        if (!$this->request->has('categories'))
            return $this;

        $childrenIdsArray = $this->childrenIds;
        $childrenIdsArray[] = $this->product_id;

        $data = [];

        foreach ($childrenIdsArray as $key => $child) {
            foreach ($this->request->categories as $index => $category) {
                $data[] = [
                    'product_id' => $child,
                    'category_id' => $category,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ];
            }
        }

        if (ProductCategory::insert($data)) {
            return $this;
        }

        throw new Exception('Error while storing product categories');
    }

    private function storeAdditionalFields()
    {
        if ($this->request->has('fields')) {
            $fieldsArray = $this->request->fields ?? [];

            $data = collect($this->request->fields);
            $data->each(function ($item, $key) {
                $item['product_id'] = $this->product_id;
                $item['created_at'] = Carbon::now()->toDateTimeString();
                $item['updated_at'] = Carbon::now()->toDateTimeString();
            });


            foreach ($this->request->fields as $field => $value) {

                if ($fieldsArray[$field]["type"] == 'select')
                    $fieldsArray[$field]["value"] =  null;
                else {
                    $fieldsArray[$field]["value"] = json_encode($value['value']);
                    $fieldsArray[$field]["field_value_id"] = null;
                }

                $fieldsArray[$field]["product_id"] = $this->product_id;
                $fieldsArray[$field]["created_at"] = Carbon::now()->toDateTimeString();
                $fieldsArray[$field]["updated_at"] = Carbon::now()->toDateTimeString();
                unset($fieldsArray[$field]['type']);
            }
            ProductField::insert($fieldsArray);
        }

        return $this;
    }

    private function storeAdditionalImages()
    {
        if ($this->request->has('images')) {

            $childrenIdsArray = $this->childrenIds;
            $childrenIdsArray[] = $this->product_id;

            $data = [];
            foreach ($childrenIdsArray as $key => $child) {
                foreach ($this->request->images as $index => $image) {
                    $imagePath = uploadImage($image['image'],  config('images_paths.product.images'));

                    $data[] = [
                        'product_id' => $child,
                        'image' => $imagePath,
                        'title' => (array)json_decode($image['title']),
                        'created_at'  => today()->toDateString(),
                        'updated_at' => today()->toDateString(),
                    ];
                }
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
            $pricesArray =  $this->request->prices ?? [];
            foreach ($this->request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $this->product_id;
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
                    'length' => $variation['length'],
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

    public function createProduct($data)
    {
        try {

            $product = new Product();
            $product->name = json_encode($data['name']);
            $product->slug = $data['slug'];
            $product->code = $data['code'];
            $product->sku = $data['sku'];
            $product->type = $data['type'];
            $product->quantity = $data['quantity'] ?? 0;
            $product->reserved_quantity = $data['reserved_quantity'] ?? 0;
            $product->minimum_quantity = $data['minimum_quantity'] ?? 0;
            $product->summary = json_encode($data['summary']);
            $product->specification = json_encode($data['specification']);
            if ($data['image'])
                $product->image = uploadImage($data['file']('image'), config('images_paths.product.images'));

            $product->meta_title = json_encode($data['meta_title']);
            $product->meta_description = json_encode($data['meta_description']);
            $product->meta_keyword = json_encode($data['meta_keyword']);
            $product->description = json_encode($data['description']);
            $product->status = $data['status'];
            $product->barcode = $data['barcode'];
            $product->height = $data['height'];
            $product->width = $data['width'];
            $product->is_disabled = 0;
            $product->length = $data['length'];
            $product->weight = $data['weight'];
            $product->is_default_child = $data['is_default_child'] ?? 0;
            $product->parent_product_id = $data['parent_product_id'] ?? null;
            $product->category_id = $data['category_id'];
            $product->unit_id = $data['unit_id'];
            $product->brand_id = $data['brand_id'];
            $product->tax_id = $data['tax_id'];
            $product->products_statuses_id = $data['products_statuses_id'];
            $product->save();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        return $product;
    }
}
