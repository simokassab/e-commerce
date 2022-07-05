<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\MainController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Price\Price;
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

class ProductController extends MainController
{
    const OBJECT_NAME = 'objects.product';
    const relations=['parent','children','defaultCategory','unit','tax','brand'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->method()=='POST'){

        }
        return $this->successResponsePaginated(ProductResource::class,Product::class,self::relations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $objectsArray=[];

        $prices=Price::with('currency')->where('is_virtual', 0)->get();
        foreach ($prices as $price => $value) {
            $object = (object)[];
            $object->id=$value['id'];
            $object->name=$value['name'];
            $object->currency_code=$value->currency->code;
            $objectsArray[]=$object;
        }
        return $this->successResponse(['prices'=>$objectsArray]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {

        $product = new Product();
        $product->name = json_encode($request->name);
        $product->slug = $request->slug;
        $product->code = $request->code;
        $product->sku = $request->sku;
        $product->type = $request->type;
        $product->quantity = $request->quantity ?? 0;
        $product->reserved_quantity = $request->reserved_quantity ?? 0;
        $product->minimum_quantity = $request->minimum_quantity ?? 0;

        $product->summary = json_encode($request->summary);
        $product->specification = json_encode($request->specification);
        if($request->image)
            $product->image= $this->imageUpload($request->file('image'),config('images_paths.product.images'));

        $product->meta_title = json_encode($request->meta_title);
        $product->meta_description = json_encode($request->meta_description);
        $product->meta_keyword = json_encode($request->meta_keyword);
        $product->description = json_encode($request->description);
        $product->status = $request->status;
        $product->barcode = $request->barcode;
        $product->height = $request->height;
        $product->width = $request->width;
        $product->is_disabled=0;
        $product->length = $request->length;
        $product->weight = $request->weight;
        $product->is_default_child = $request->is_default_child ?? 0;

        $product->parent_product_id = $request->parent_product_id;
        $product->category_id= $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->brand_id = $request->brand_id;
        $product->tax_id = $request->tax_id;
        $product->products_statuses_id = $request->products_statuses_id;
        $product->save();

        if($request->has('categories')){
            $categoriesArray=$request->categories;
            foreach ($request->categories as $category => $value){
                $categoriesArray[$category]["product_id"] = $product->id;
                $categoriesArray[$category]["created_at"] = Carbon::now()->toDateTimeString();
                $categoriesArray[$category]["updated_at"] = Carbon::now()->toDateTimeString();
            }
                ProductCategory::insert($categoriesArray);
            }
        if($request->has('fields')){
            $fieldsArray=$request->fields;
            foreach ($request->fields as $field => $value){
                if($fieldsArray[$field]["type"]=='select')
                    $fieldsArray[$field]["value"] = null;
                else{
                    $fieldsArray[$field]["field_value_id"] = null;
                    $fieldsArray[$field]["value"] = json_encode($value['value']);

                }

                $fieldsArray[$field]["product_id"] = $product->id;
                $fieldsArray[$field]["created_at"] = Carbon::now()->toDateTimeString();
                $fieldsArray[$field]["updated_at"] = Carbon::now()->toDateTimeString();
                unset($fieldsArray[$field]['type']);

            }
              ProductField::insert($fieldsArray);
        }

        if($request->has('images')){
        $imagesArray=$request->images;
        foreach ($request->images as $image => $value){
          $imagesArray[$image]["product_id"] = $product->id;
          $imagesArray[$image]["title"] = json_encode($value['title']);
          $imagesArray[$image]["created_at"] = Carbon::now()->toDateTimeString();
          $imagesArray[$image]["updated_at"] = Carbon::now()->toDateTimeString();
        }
            ProductImage::insert($imagesArray);
        }

        if($request->has('labels')){
        $labelsArray=$request->labels;
        foreach ($request->labels as $label => $value){
            $labelsArray[$label]["product_id"] = $product->id;
            $labelsArray[$label]["created_at"] = Carbon::now()->toDateTimeString();
            $labelsArray[$label]["updated_at"] = Carbon::now()->toDateTimeString();
        }
            ProductLabel::insert($labelsArray);
        }

        if($request->has('prices')){
            $pricesArray=$request->prices;
            foreach ($request->prices as $price => $value){
                $pricesArray[$price]["product_id"] = $product->id;
                $pricesArray[$price]["created_at"] = Carbon::now()->toDateTimeString();
                $pricesArray[$price]["updated_at"] = Carbon::now()->toDateTimeString();
            }
                ProductPrice::insert($pricesArray);
            }

        if($request->has('related_products')){
            $relatedProductsArray=$request->related_products;
            foreach ($request->related_products as $related_product => $value){
                $relatedProductsArray[$related_product]["product_id"] = $product->id;
                $relatedProductsArray[$related_product]["created_at"] = Carbon::now()->toDateTimeString();
                $relatedProductsArray[$related_product]["updated_at"] = Carbon::now()->toDateTimeString();
            }
                ProductRelated::insert($relatedProductsArray);
            }

        if($request->has('tags')){
            $tagsArray=$request->tags;
            foreach ($request->tags as $tag => $value){
                $tagsArray[$tag]["product_id"] = $product->id;
                $tagsArray[$tag]["created_at"] = Carbon::now()->toDateTimeString();
                $tagsArray[$tag]["updated_at"] = Carbon::now()->toDateTimeString();
            }
                ProductTag::insert($tagsArray);
            }


        }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
