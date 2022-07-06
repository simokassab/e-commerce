<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\MainController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Field\Field;
use App\Models\Label\Label;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductStatus;
use App\Models\Tax\Tax;
use App\Models\Unit\Unit;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends MainController
{
    const OBJECT_NAME = 'objects.product';
    const relations=['parent','children','defaultCategory','unit','tax','brand'];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
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
        $PriceArray=[];
        $prices=Price::with('currency')->where('is_virtual', 0)->get();
        foreach ($prices as $price => $value) {
            $object = (object)[];
            $object->id=$value['id'];
            $object->name=$value['name'];
            $object->currency_code=$value->currency->code;
            $PriceArray[]=$object;
        }

        $fields= Field::with('fieldValue')
        ->whereEntity('brand')
        ->get();

        $labels= Label::whereEntity('product')->get();

        $brands = Brand::all();
        $units = Unit::all();
        $taxes= Tax::all();
        $catgories = Category::all();
        $statuses=ProductStatus::all();

        return $this->successResponse([
            'fields'=>$fields,
            'labels'=>$labels,
            'prices'=>$PriceArray,
            'brands'=>$brands,
            'units'=>$units,
            'taxes'=> $taxes,
            'catgories'=> $catgories,
            'statuses'=> $statuses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
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

        $this->productService->storeAdditionalProductData($request,$product->id);

        DB::commit();
        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
        'product' =>  new ProductResource($product)
          ]);
        }catch (\Exception $ex) {
            DB::rollBack();
            return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME),]),
            'message' => $ex->getMessage()
             ]);

        }}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->successResponse(['product' =>  new ProductResource($product)]);

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

    }
    public function toggleStatus(Request $request ,$id){

        $request->validate(['is_disabled' => 'boolean|required']);
        $product = Product::findOrFail($id);
        $product->is_disabled=$request->is_disabled;
        if(!$product->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'product' =>  new ProductResource($product)
        ]);

    }

    public function updateSortValues(StoreProductRequest $request){
        batch()->update($product = new Product(),$request->order,'id');
            return $this->successResponsePaginated(ProductResource::class,Product::class,self::relations);
    }



}
