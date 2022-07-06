<?php

namespace App\Services\Product;

use App\Models\Product\ProductCategory;
use App\Models\Product\ProductField;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductLabel;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductRelated;
use App\Models\Product\ProductTag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductService
{
public $request,$product_id;

    public function storeAdditionalProductData(Request $request, $product_id)
    {

        $this->request = $request;
        $this->product_id = $product_id;

        self::storeAdditionalCategrories()
        ->storeAdditionalFields($request, $product_id)
        ->storeAdditionalImages($request, $product_id)
        ->storeAdditionalLabels($request, $product_id)
        ->storeAdditionalPrices($request, $product_id)
        ->storeAdditionalTags($request, $product_id)
        ->storeAdditionalBundle($request, $product_id);
    }

    private function storeAdditionalCategrories(){
        if ($this->request->has('categories')) {
            dd($this->product_id);
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
    private function storeAdditionalFields(Request $request, $product_id){
        if ($request->has('fields')) {
            $fieldsArray = $request->fields ?? [];
            foreach ($request->fields as $field => $value) {
                if ($fieldsArray[$field]["type"] == 'select')
                    $fieldsArray[$field]["value"] = null;
                else {
                    $fieldsArray[$field]["field_value_id"] = null;
                    $fieldsArray[$field]["value"] = json_encode($value['value']);
                }

                $fieldsArray[$field]["product_id"] = $product_id;
                $fieldsArray[$field]["created_at"] = Carbon::now()->toDateTimeString();
                $fieldsArray[$field]["updated_at"] = Carbon::now()->toDateTimeString();
                unset($fieldsArray[$field]['type']);
            }
            ProductField::insert($fieldsArray);
        }

        return $this;
    }
    private function storeAdditionalImages(Request $request, $product_id){
        if ($request->has('images')) {
            $imagesArray = $request->images ?? [];
            foreach ($request->images as $image => $value) {
                $imagesArray[$image]["product_id"] = $product_id;
                $imagesArray[$image]["title"] = json_encode($value['title']);
                $imagesArray[$image]["created_at"] = Carbon::now()->toDateTimeString();
                $imagesArray[$image]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductImage::insert($imagesArray);
        }

        return $this;
    }
    private function storeAdditionalLabels(Request $request, $product_id){
        if ($request->has('labels')) {
            $labelsArray = $request->labels ?? [];
            foreach ($request->labels as $label => $value) {
                $labelsArray[$label]["product_id"] = $product_id;
                $labelsArray[$label]["created_at"] = Carbon::now()->toDateTimeString();
                $labelsArray[$label]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductLabel::insert($labelsArray);
        }

        return $this;
    }
    private function storeAdditionalPrices(Request $request, $product_id){
        if ($request->has('prices')) {
            $pricesArray = $request->prices ?? [];
            foreach ($request->prices as $price => $value) {
                $pricesArray[$price]["product_id"] = $product_id;
                $pricesArray[$price]["created_at"] = Carbon::now()->toDateTimeString();
                $pricesArray[$price]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductPrice::insert($pricesArray);
        }
        return $this;
    }
    private function storeAdditionalTags(Request $request, $product_id){
        if ($request->has('tags')) {
            $tagsArray = $request->tags ?? [];
            foreach ($request->tags as $tag => $value) {
                $tagsArray[$tag]["product_id"] = $product_id;
                $tagsArray[$tag]["created_at"] = Carbon::now()->toDateTimeString();
                $tagsArray[$tag]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductTag::insert($tagsArray);
        }
        return $this;

    }
    private function storeAdditionalBundle(Request $request, $product_id){
        if ($request->type == 'bundle') {
            $relatedProductsArray = $request->related_products ?? [];
            foreach ($request->related_products as $related_product => $value) {
                $relatedProductsArray[$related_product]["parent_product_id"] = $product_id;
                $relatedProductsArray[$related_product]["created_at"] = Carbon::now()->toDateTimeString();
                $relatedProductsArray[$related_product]["updated_at"] = Carbon::now()->toDateTimeString();
            }
            ProductRelated::insert($relatedProductsArray);
        }

        return $this;
    }
}
