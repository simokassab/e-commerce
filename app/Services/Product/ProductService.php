<?php

namespace App\Services\Product;

use App\Http\Controllers\MainController;
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
public $request,$product_id;

    public function storeAdditionalProductData(Request $request, $product_id)
    {

        $this->request = $request;
        $this->product_id = $product_id;

        self::storeAdditionalCategrories()
            ->storeAdditionalFields()
            ->storeAdditionalImages()
            ->storeAdditionalLabels()
            ->storeAdditionalTags()
            ->storeAdditionalBundle()
            ->storeAdditionalPrices();

    }

    private function storeAdditionalCategrories(){
        if ($this->request->has('categories')) {
            $categoriesArray = $this->request->categories ?? [];
            $categoriesChildrenArray = $this->request->categories ?? [];
            foreach ($this->request->categories as $category => $value) {
                $categoriesArray[$category]["product_id"] = $this->product_id;
                $categoriesArray[$category]["created_at"] = Carbon::now()->toDateTimeString();
                $categoriesArray[$category]["updated_at"] = Carbon::now()->toDateTimeString();

                $categoriesChildrenArray[$category]["product_id"] = $this->product_id+1;
                $categoriesChildrenArray[$category]["created_at"] = Carbon::now()->toDateTimeString();
                $categoriesChildrenArray[$category]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            $categoiresArrayMixed=array_merge($categoriesArray,$categoriesChildrenArray);
            ProductCategory::insert($categoiresArrayMixed);
        }

        return $this;
    }
    private function storeAdditionalFields(){
        if ($this->request->has('fields')) {
            $fieldsArray = $this->request->fields ?? [];

            $data = collect($this->request->fields);
            $data->each(function ($item, $key) {
                $item['product_id'] = $this->product_id;
                $item['created_at'] = Carbon::now()->toDateTimeString();
                $item['updated_at'] = Carbon::now()->toDateTimeString();
            });


            foreach ($this->request->fields as $field => $value) {

                // $fieldsArray[$field]["value"] = [
                //     'select' => null,
                //     'default' => json_encode($value['value']),
                // ][$fieldsArray[$field]["type"]];

                // $fieldsArray[$field]["field_value_id"] = $fieldsArray[$field]["type"] != 'select' ? null : ;


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
    private function storeAdditionalImages(){
        if ($this->request->has('images')) {
            $imagesArray = $this->request->images ?? [];
            foreach ($this->request->images as $image => $value) {
                $imagesArray[$image]["product_id"] = $this->product_id;
                $imagesArray[$image]["title"] = json_encode($value['title']);
                $imagesArray[$image]["created_at"] = Carbon::now()->toDateTimeString();
                $imagesArray[$image]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductImage::insert($imagesArray);
        }

        return $this;
    }
    private function storeAdditionalLabels(){
        if ($this->request->has('labels')) {
            $labelsArray = $this->request->labels ?? [];
            foreach ($this->request->labels as $label => $value) {
                $labelsArray[$label]["product_id"] = $this->product_id;
                $labelsArray[$label]["created_at"] = Carbon::now()->toDateTimeString();
                $labelsArray[$label]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductLabel::insert($labelsArray);
        }

        return $this;
    }
    private function storeAdditionalPrices(){
        if ( $this->request ->has('prices')) {
            $pricesArray =  $this->request ->prices ?? [];
            foreach ( $this->request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $this->product_id;
                $pricesArray[$price]["created_at"] = Carbon::now()->toDateTimeString();
                $pricesArray[$price]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($pricesArray);
        }
        return $this;
        }



    private function storeAdditionalTags(){
        if ($this->request->has('tags')) {
            $tagsArray = $this->request->tags ?? [];
            foreach ($this->request->tags as $tag => $value) {
                $tagsArray[$tag]["product_id"] = $this->product_id;
                $tagsArray[$tag]["created_at"] = Carbon::now()->toDateTimeString();
                $tagsArray[$tag]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductTag::insert($tagsArray);
        }
        return $this;
    }
    private function storeAdditionalBundle(){
        if ($this->request->type == 'bundle') {
            $relatedProductsArray = $this->request->related_products ?? [];
            foreach ($this->request->related_products as $related_product => $value) {
                $relatedProductsArray[$related_product]["parent_product_id"] = $this->product_id;
                $relatedProductsArray[$related_product]["created_at"] = Carbon::now()->toDateTimeString();
                $relatedProductsArray[$related_product]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductRelated::insert($relatedProductsArray);
        }

        return $this;
    }

    public static function deleteRelatedDataForProduct(Product $product){

        // DB::beginTransaction();
        // try {
            //code...

        // $productType=Product::find($product->id)->type ?? '';
         $productType=$product->type ?? '';
        if($productType=='variable'){

            $productChildren = $product->children->pluck('id');
            dd($productChildren);
                Product::whereIn('id',$productChildren)->delete();
                ProductCategory::whereIn('product_id',$productChildren)->delete();
                ProductField::whereIn('product_id',$productChildren)->delete();
                ProductImage::whereIn('product_id',$productChildren)->delete();
                ProductLabel::whereIn('product_id',$productChildren)->delete();
                ProductPrice::whereIn('product_id',$productChildren)->delete();
                ProductTag::whereIn('product_id',$productChildren)->delete();

        }

            ProductRelated::whereIn('parent_product_id',$product->id)->delete();
            ProductCategory::where('product_id',$product->id)->delete();
            ProductField::where('product_id',$product->id)->delete();
            ProductImage::whereIn('product_id',$product->id)->delete();
            ProductLabel::where('product_id',$product->id)->delete();
            ProductPrice::where('product_id',$product->id)->delete();
            ProductTag::where('product_id',$product->id)->delete();
        //     DB::commit();
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }


    }

    public function storeVariationsAndPrices(Request $request,$product){

        try{
            $data = [];
            throw_if(!$request->product_variations, Exception::class, 'No variations found');
            foreach ($request->product_variations as $variation) {
                $productVariationsArray = [
                            'name' => json_encode($request->name),
                            'slug' => $variation['slug'],
                            'code' => $variation['code'],
                            'type' =>'variable_child',
                            'sku'=> $variation['sku'],
                            'quantity' => $variation['quantity'],
                            'reserved_quantity'=> $variation['reserved_quantity'],
                            'minimum_quantity' => $variation['minimum_quantity'],
                            'height' => $variation['height'],
                            'width'=> $variation['width'],
                            'length'=> $variation['length'],
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
                        ];
                        $productVariation = Product::create($productVariationsArray);

                        $pricesInfo = $request->isSameAsParent ? $request->prices : $variation['child_prices'];
                        
                        foreach ($pricesInfo as $key => $price) {
                            $pricesInfo[$key]['product_id'] = $productVariation->id;
                        }
                        $data[] = $pricesInfo;


            }   
            $finalPricesCollect=collect($data)->collapse()->toArray();

            ProductPrice::insert($finalPricesCollect);

        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }

    }

    public function createProduct($data){
        try{

            $mainController = new MainController();

            $product=new Product();
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
            if($data['image'])
                $product->image= $mainController->imageUpload($data['file']('image'),config('images_paths.product.images'));

            $product->meta_title = json_encode($data['meta_title']);
            $product->meta_description = json_encode($data['meta_description']);
            $product->meta_keyword = json_encode($data['meta_keyword']);
            $product->description = json_encode($data['description']);
            $product->status = $data['status'];
            $product->barcode = $data['barcode'];
            $product->height = $data['height'];
            $product->width = $data['width'];
            $product->is_disabled=0;
            $product->length = $data['length'];
            $product->weight = $data['weight'];
            $product->is_default_child = $data['is_default_child'] ?? 0;
            $product->parent_product_id = $data['parent_product_id'] ?? null;
            $product->category_id= $data['category_id'];
            $product->unit_id = $data['unit_id'];
            $product->brand_id = $data['brand_id'];
            $product->tax_id = $data['tax_id'];
            $product->products_statuses_id = $data['products_statuses_id'];
            $product->save();
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }

        return $product;
    }


    public function inhertPrices($prices,$childrenIds){
        if ($prices) {
            dd($prices);
            $childrenArray = [];
            foreach ($childrenIds as $childId => $value) {
                $childrenArray[$childId]["price_id"] =$prices[0]["price_id"];
                $childrenArray[$childId]["price"] =  $prices[0]["price"];
                $childrenArray[$childId]["discounted_price"] = $prices[0]["discounted_price"];
                $childrenArray[$childId]["product_id"] = $childrenIds[$childId];
                $childrenArray[$childId]["created_at"] = Carbon::now()->toDateTimeString();
                $childrenArray[$childId]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($childrenArray);
        }
    }

    
}
