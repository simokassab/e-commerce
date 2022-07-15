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
            ->storeAdditionalBundle();

            if(!$request->isSamePriceAsParent){
                self::storeAdditionalPrices();
            }

    }

    private function storeAdditionalCategrories(){
        if ($this->request->has('categories')) {
            $categoriesArray = $this->request->categories ?? [];
            foreach ($this->request->categories as $category => $value) {
                $categoriesArray[$category]["product_id"] = $this->product_id;
                $categoriesArray[$category]["created_at"] = Carbon::now()->toDateTimeString();
                $categoriesArray[$category]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductCategory::insert($categoriesArray);
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
    
        DB::beginTransaction();
        try {
            //code...
      
        $productType=Product::find($product->id)->type ?? '';

        if($productType=='variable'){
         
            $productChildren = $product->children->pluck('id');

                Product::whereIn('id',$productChildren)->delete();
                ProductCategory::whereIn('product_id',$productChildren)->delete();
                ProductField::whereIn('product_id',$productChildren)->delete();
                ProductImage::whereIn('product_id',$productChildren)->delete();
                ProductLabel::whereIn('product_id',$productChildren)->delete();
                ProductPrice::whereIn('product_id',$productChildren)->delete();
                ProductTag::whereIn('product_id',$productChildren)->delete();
                
        }
        elseif($productType=='bundle'){

            $productChildren =$product->productRelatedChildren->pluck('id');

            Product::whereIn('id',$productChildren)->delete();
            ProductCategory::whereIn('product_id',$productChildren)->delete();
            ProductField::whereIn('product_id',$productChildren)->delete();
            ProductImage::whereIn('product_id',$productChildren)->delete();
            ProductLabel::whereIn('product_id',$productChildren)->delete();
            ProductPrice::whereIn('product_id',$productChildren)->delete();
            ProductTag::whereIn('product_id',$productChildren)->delete();
            ProductRelated::whereIn('parent_product_id',$productChildren)->delete();
        
        }

            ProductCategory::where('product_id',$product->id)->delete();
            ProductField::where('product_id',$product->id)->delete();
            ProductImage::whereIn('product_id',$product->id)->delete();
            ProductLabel::where('product_id',$product->id)->delete();
            ProductPrice::where('product_id',$product->id)->delete();
            ProductTag::where('product_id',$product->id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
        }


    }
}
