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

            if(!$request->isSamePriceAsParent){
                self::storeChildPrices();
            }

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
            foreach ($this->request->fields as $field => $value) {
                if ($fieldsArray[$field]["type"] == 'select')
                    $fieldsArray[$field]["value"] = null;
                else {
                    $fieldsArray[$field]["field_value_id"] = null;
                    $fieldsArray[$field]["value"] = json_encode($value['value']);
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

    private function storeChildPrices(){
        if($this->request->has('child_prices')){
            $childPricesArray = $this->request->child_prices ?? [];
            foreach ($this->request->child_prices as $childPrice => $value) {
                $childPricesArray[$childPrice]["product_id"] = $this->product_id+1;
                $childPricesArray[$childPrice]["created_at"] = Carbon::now()->toDateTimeString();
                $childPricesArray[$childPrice]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($childPricesArray);
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

    public function storeVariations(Request $request, $productId){
        $productVariationsArray=[];
        foreach ($request->product_variations as $key => $variation) {
                    $productVariationsArray[$key]['name']=  json_encode($request->name);
                    $productVariationsArray[$key]['slug'] = $variation['slug'];
                    $productVariationsArray[$key]['code'] = $variation['code'];
                    $productVariationsArray[$key]['type'] ='variable_child';
                    $productVariationsArray[$key]['sku']= $variation['sku'];
                    $productVariationsArray[$key]['quantity'] = $variation['quantity'];
                    $productVariationsArray[$key]['reserved_quantity']= $variation['reserved_quantity'];
                    $productVariationsArray[$key]['minimum_quantity'] = $variation['minimum_quantity'];
                    $productVariationsArray[$key]['height'] = $variation['height'];
                    $productVariationsArray[$key]['width']= $variation['width'];
                    $productVariationsArray[$key]['length']= $variation['length'];
                    $productVariationsArray[$key]['weight'] = $variation['weight'];
                    $productVariationsArray[$key]['barcode'] = $variation['barcode'];
                    $productVariationsArray[$key]['category_id'] = $request->category_id;
                    $productVariationsArray[$key]['unit_id'] = $request->unit_id;
                    $productVariationsArray[$key]['tax_id'] = $request->tax_id;
                    $productVariationsArray[$key]['brand_id'] = $request->brand_id;
                    $productVariationsArray[$key]['summary'] = json_encode($request->summary);
                    $productVariationsArray[$key]['specification'] = json_encode($request->specification);
                    $productVariationsArray[$key]['meta_title'] = json_encode($request->meta_title);
                    $productVariationsArray[$key]['meta_description'] = json_encode($request->meta_description);
                    $productVariationsArray[$key]['description'] = json_encode($request->description);
                    $productVariationsArray[$key]['status'] = $request->status;
                    $productVariationsArray[$key]['parent_product_id'] = $productId;
                    $productVariationsArray[$key]['products_statuses_id'] = $request->products_statuses_id;

                    // if($request->isSamePriceAsParent){
                    //     ProductPrice::inhertPrices($request, $productId+1);
                    // }
        }
        $newChildrenProducts = Product::insert($productVariationsArray);
        if($newChildrenProducts){
            $childrenIds=Product::where('parent_product_id',$productId)->pluck('id');
                if($request->isSamePriceAsParent){
                        ProductPrice::inhertPrices($request, $childrenIds);
                    }
        }
    }
}
